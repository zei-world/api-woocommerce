---
title: /v2/validation/offer/{offerId}/{profileId}
position: 2.1
type: get
description: Validate an offer {offerId} for {profileId}
right_code: |
  ~~~ json
    {
      "success": true,
      "message": "Offer validated for..."
    }
  ~~~
  {: title="Response" }

  ~~~ json
  {
    "success": false,
    "message": "[OFFER VALIDATION] ..."
  }
  ~~~
  {: title="Error" }
---
id
: Your ZEI API id

secret 
: Your ZEI API secret

amount
: Applied quantity (default : 1) [OPTIONAL]

**Logique (FR)** :

- Vérifie les valeurs des params envoyés et si l'IP cliente est présente
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie la validité de l'offre (non supprimée, activée, contrat signé et avec la catégorie "En ligne")
- Vérifie si l'offre peut s'appliquer à un utilisateur, une entreprise ou une association (B2B / B2C)
- Valide l'offre pour l'utilisateur, l'entreprise ou l'association
