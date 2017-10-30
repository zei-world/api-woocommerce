---
title: /v3/offers/{offerId}/validate/email
position: 2.3
type: get
description: Validate an offer {offerId} for an email address
right_code: |
  ~~~ json
    {
      "success": true,
      "message": "Validation de l'offre effectuée !"
    }
  ~~~
  {: title="Response" }

  ~~~ json
  {
    "success": false,
    "code": "error code",
    "message": "error message"
  }
  ~~~
  {: title="Error" }
---
id
: Your ZEI API id

secret 
: Your ZEI API secret

email
: The user's email

units
: Applied quantity (default : 1) [OPTIONAL]

**Logique (FR)** :

- Vérifie les valeurs des params envoyés et si l'IP cliente est présente
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie la validité de l'offre (non supprimée, activée, contrat signé et avec le statut "En ligne")
- Vérifie si l'offre peut s'appliquer à un utilisateur
- Valide l'offre pour l'utilisateur
