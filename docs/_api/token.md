---
title: /token
position: 1.0
type: get
description: Generate an auth token code
right_code: |
  ~~~ json
    {
      "success": true,
      "token": "02047c83e17e11e69c5c029e5cda3cbb",
      "message": "Token ready for usage, have some turfu :)"
    }
  ~~~
  {: title="Response" }

  ~~~ json
  {
    "success": false,
    "message": "[TOKEN] ..."
  }
  ~~~
  {: title="Error" }
---
id
: Your ZEI API id

secret
: Your ZEI API secret

b2b
: Display company and organization profiles (default : 1) [OPTIONAL]

b2c
: Display user profiles (default : 1) [OPTIONAL]

locale
: Language to use : "fr" (default) or "en"  [OPTIONAL]

"b2b" and "b2c" parameters must be "1" for true or "0" for false
{: .warning }

~~~ php
json_decode(
    file_get_contents(
        "https://zero-ecoimpact.org/api/token",
        false,
        stream_context_create([
            'http' => [ 'method' => "GET", 'timeout' => 2, 'header' => [
                "id" => YOUR_API_KEY,
                "secret" => YOUR_API_SECRET
            ]],
            'ssl'  => [ "verify_peer" => false, "verify_peer_name" => false ]
        ])
    ), true
);
~~~
{: title="PHP" }

**Logique (FR)** :

- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité de l'id et du secret
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie si des tokens sont libres (durée de vie de 40 minutes dépassée)
- Génère un nouveau si aucun n'est disponible
- Renvoie le token
