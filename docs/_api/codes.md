---
title: /company/codes
position: 3.1
type: get
description: Check if a reward code is valid
right_code: |
  ~~~ json
    {
      "success": true,
      "message": 25
    }
  ~~~
  {: title="Response" }

  ~~~ json
  {
    "success": false,
    "message": "[CODES] ..."
  }
  ~~~
  {: title="Error" }
---
id
: Your ZEI API id

secret
: Your ZEI API secret

**Logique (FR)** :

- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité du token
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Renvoie la liste
