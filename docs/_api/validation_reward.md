---
title: /v2/validation/reward/{code}
position: 3.1
type: get
description: Check and validate a reward code
right_code: |
  ~~~ json
    {
      "success": true,
      "message": "Reward validated for..."
    }
  ~~~
  {: title="Response" }

  ~~~ json
  {
    "success": false,
    "message": "[REWARD VALIDATION] ..."
  }
  ~~~
  {: title="Error" }
---
id
: Your ZEI API id

secret 
: Your ZEI API secret

confirm
: Check the code (1) or validate (0) (default : 1) [OPTIONAL]

**Logique (FR)** :

- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité du token
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie la validité de la récompense (non supprimée, activée, contrat signé et avec la catégorie "En ligne")
- Vérifie si la récompense peut s'appliquer à un utilisateur, une entreprise ou une association (B2B / B2C)
- Valide la récompense pour l'utilisateur, l'entreprise ou l'association
- Consomme le code correspondant pour indiquer qu'il a été utilisé
- Supprime le token
