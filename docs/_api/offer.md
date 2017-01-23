---
title: /company/offer
position: 2.1
type: get
description: Validate an offer
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
    "message": "[OFFER] ..."
  }
  ~~~
  {: title="Error" }
---
token
: Generated valid token

offer
: Your Company offer id

amount
: Applied quantity (default : 1) [OPTIONAL]

**Logique (FR)** :

- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité du token
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie la validité de l'offre (non supprimée, activée, contrat signé et avec la catégorie "En ligne")
- Vérifie si l'offre peut s'appliquer à un utilisateur, une entreprise ou une association (B2B / B2C)
- Valide l'offre pour l'utilisateur, l'entreprise ou l'association
- Supprime le token
