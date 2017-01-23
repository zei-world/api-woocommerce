---
title: /company/rewards
position: 3.0
type: get
description: Get your rewards list
right_code: |
  ~~~ json
    {
      "success": true,
      "message": [
        42: "A reward example"
      ]
    }
  ~~~
  {: title="Response" }

  ~~~ json
  {
    "success": false,
    "message": "[REWARDS] ..."
  }
  ~~~
  {: title="Error" }
---
token
: Generated valid token

**Logique (FR)** :

- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité du token
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Renvoie la liste
