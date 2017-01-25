<?php
/**
 * ZEI WC. cart
 *
 * @package  ZEI_WC_Cart
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Cart')):

include_once 'ZEI_WC_API.php';

class ZEI_WC_Cart {
    public function __construct() {
        // Coupon
        add_filter('woocommerce_get_shop_coupon_data', array($this, "coupon"), 10, 2);

        // Module
        $options = get_option('woocommerce_zei-wc_settings');
        if(!isset($options['zei_module_location']) || $options['zei_module_location'] == 0) {
            add_action('woocommerce_after_order_notes', array($this, "module"));
        } else if(isset($options['zei_module_location']) && $options['zei_module_location'] == 1) {
            add_action('woocommerce_after_checkout_billing_form', array($this, "module"));
        } else if(isset($options['zei_module_location']) && $options['zei_module_location'] == 2) {
            add_action('woocommerce_purchase_note_order_statuses', array($this, "moduleValidation"));
        }

        // Token
        add_action('woocommerce_new_order', array($this, "token"));

        // Points validated
        add_action('woocommerce_order_status_completed', array($this, "completed"));
    }

    private function couponExists($coupon) {
        global $wpdb;
        return 0 < $wpdb->get_var("select count(ID) from $wpdb->posts"
            ." where post_title = '".$coupon."' and post_status = 'publish'"
        );
    }

    public function coupon($valid, $coupon) {
        if(substr($coupon, 0, 11) === 'zei_reward_') return false;
        $reward = ZEI_WC_API::codesValidate(strtoupper($coupon));
        if($reward && !$this->couponExists($coupon)) {
            if($this->couponExists("zei_reward_".$reward)) {
                $model = new WC_Coupon('zei_reward_'.$reward);
                if($model->exists) {
                    $original = get_post($model->id);
                    $new = wp_insert_post(array(
                        'post_title' => $coupon,
                        'post_type' => 'shop_coupon',
                        'to_ping' => $original->to_ping,
                        'menu_order' => $original->menu_order,
                        'post_content' => '',
                        'post_excerpt' => $original->post_excerpt,
                        'post_name' => $coupon,
                        'post_parent' => $original->post_parent,
                        'post_password' => $original->post_password,
                        'post_status' => 'publish',
                        'comment_status' => $original->comment_status,
                        'ping_status' => $original->ping_status,
                        'post_author' => $original->post_author
                    ));
                    foreach(get_post_meta($model->id) as $key => $value) add_post_meta($new, $key, $value[0]);
                    update_post_meta($new, 'usage_limit', 1);
                    update_post_meta($new, '_zei_reward', $reward);
                    return true;
                }
            }
        }
        return $valid;
    }

    public function module() {
        global $woocommerce;

        $token = ZEI_WC_API::getToken();

        if($token) {
            $options = get_option('woocommerce_zei-wc_settings');
            $display = $options && isset($options['zei_global_offer']) && $options['zei_global_offer'] != 0;
            if(!$display) {
                foreach($woocommerce->cart->get_cart() as $item) {
                    if(get_post_meta($item['product_id'], '_zei_offer', true)) {
                        $display = true;
                        break;
                    }
                }
            }
            if($display) {
                $url = ZEI_WC_API::getModuleUrl($token, true, true);
                if($url) echo "<object id=\"ZEI\" width=\"360px\" height=\"60px\" data=\""
                    .$url."\"></object>";
            }
        }
    }

    private function validateOffer($token, $item) {
        $options = get_option('woocommerce_zei-wc_settings');
        $offerId = ($options && isset($options['zei_global_offer']) && $options['zei_global_offer'] != 0)
            ? $options['zei_global_offer']
            : get_post_meta($item['product_id'], '_zei_offer', true);
        if($offerId) return ZEI_WC_API::validateOffer($token, $offerId, $item['qty']);
        return false;
    }

    private function jsModule() {
        echo "
            <script type='text/javascript'>
                var firstLoad = true;
                var module = document.getElementById('ZEI');
                var checkExist = setInterval(function() {
                    if(typeof module !== 'undefined') {
                        clearInterval(checkExist);
                        console.log(module);
                        module.addEventListener('load', function() {
                            if(firstLoad) {
                                firstLoad = false;
                            } else {
                                var url = window.location.href;
                                var hash = location.hash;
                                url = url.replace(hash, '');
                                if(url.indexOf('zei=') >= 0) {
                                    var prefix = url.substring(0, url.indexOf('zei'));
                                    var suffix = url.substring(url.indexOf('zei'));
                                    suffix = suffix.substring(suffix.indexOf('=') + 1);
                                    suffix = (suffix.indexOf('&') >= 0) ? suffix.substring(suffix.indexOf('&')) : '';
                                    url = prefix + 'zei=1' + suffix;
                                } else {
                                    if(url.indexOf('?') < 0) {
                                        url += '?zei=1';
                                    } else {
                                        url += '&zei=1';
                                    }
                                }
                                window.location.href = url + hash;
                            }
                        });
                    }
                }, 100);
            </script>
        ";
    }

    public function moduleValidation() {
        if(isset($_GET['order-received'])) {
            $orderId = $_GET['order-received'];
            if(!get_post_meta($orderId, '_zei_validated', true)) {
                echo "<h2>".(get_locale() == "fr_FR" ? "Vos points de récompense" : "Your rewards points")."</h2>";
                if(isset($_GET['zei']) && $_GET['zei'] == 2) {
                    $token = ZEI_WC_API::getToken();

                    $errors = false;
                    foreach(wc_get_order($orderId)->get_items() as $item) {
                        $validation = $this->validateOffer($token, $item);
                        if(!$validation) $errors = true;
                    }
                    update_post_meta($orderId, '_zei_token', $token);

                    if($errors) {
                        $text = get_locale() == "fr_FR" ? "Erreur lors de la validation :(" : "Validation error :(";
                    } else {
                        $text = get_locale() == "fr_FR" ? "Achat validé sur Zero ecoimpact !"
                            : "Purchase validated on Zero ecoimpact !";
                        update_post_meta($orderId, '_zei_validated', 1);
                    }

                    echo "<strong>".$text."</strong>";

                } else {
                    $display = isset($_GET['zei']) && $_GET['zei'] == 1;

                    if(!$display) {
                        if(get_locale() == "fr_FR") {
                            echo "<strong>Validez</strong> votre achat et <strong>gagnez</strong> vos points sur "
                                ."Zero ecoimpact en cliquant sur le bouton ci-dessous :<br/>";
                        } else {
                            echo "<strong>Validate</strong> your purchase and <strong>get</strong> your points on "
                                ."Zero ecoimpact by clicking on the following button :<br/>";
                        }
                    }

                    $this->module();
                    $this->jsModule();
                    if($display) {
                        $text = get_locale() == "fr_FR" ? "Confirmer mon achat sur ZEI" : "Confirm my purchase on ZEI";
                        $url1 =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                        $url2 = htmlspecialchars($url1, ENT_QUOTES, 'UTF-8');
                        $url3 = str_replace("zei=1", "zei=2", $url2);
                        echo "<br/><a href='".$url3."'><button type=\"submit\" class=\"button\">".$text."</button></a>";
                    }
                }
            }
        }
    }

    public function token($orderId) {
        $token = ZEI_WC_API::getToken();
        if($token) update_post_meta($orderId, '_zei_token', $token);
    }

    public function completed($orderId) {
        if(!session_id()) session_start();
        $order = wc_get_order($orderId);
        foreach($order->get_items() as $item) {
            $token = get_post_meta($orderId, '_zei_token', true);
            if($token) {
                // OFFERS
                $this->validateOffer($token, $item);

                // REWARDS
                foreach($order->get_used_coupons() as $code) {
                    $coupon = new WC_Coupon($code);
                    if($coupon->exists) {
                        $meta = get_post_meta($coupon->id, '_zei_reward', true);
                        if($meta) {
                            $reward = ZEI_WC_API::codesValidate(strtoupper($code));
                            if($reward == $meta) ZEI_WC_API::validateReward($token, $reward, $order->get_total());
                        }
                    }
                }

                // END OF TOKEN
                unset($_SESSION['zeiToken']);
            }
        }
    }
}

endif;
