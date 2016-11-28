Zero ecoimpact API
==================

/api/token
----------

**HEADERS**
- id : API id
- secret: API secret
- b2b (OPTIONAL) : Ask for company and organization profiles (0 / 1)
- b2c (OPTIONAL) : Ask for user profiles (0 / 1)
- locale (OPTIONAL) : Langage ('fr' or 'en')

**LOGIC**
- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité de l'id et du secret
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie si des tokens sont libres (durée de vie de 40 minutes dépassée)
- Génère un nouveau si aucun n'est disponible
- Renvoie le token

**ERROR** : { success: false, message: "[TOKEN] ..." }<br/>
**SUCCESS** : { success: true, message: "...", token: "..." }


/api/company/offer
------------------
**HEADERS**
- token : generated valid token
- offer: offer id
- amount: applied quantity

**LOGIC**
- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité du token
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie la validité de l'offre (non supprimée, activée, contrat signé et avec la catégorie "En ligne")
- Vérifie si l'offre peut s'appliquer à un utilisateur, une entreprise ou une association (B2B / B2C)
- Valide l'offre pour l'utilisateur, l'entreprise ou l'association
- Supprime le token

**ERROR** : { success: false, message: "[OFFER] ..." }<br/>
**SUCCESS** : { success: true, message: "..." }


/api/company/reward
-------------------
**HEADERS**
- token : generated valid token
- reward: offer id

**LOGIC**
- Vérifie les valeurs des headers envoyés et si l'IP cliente est présente
- Vérifie la validité du token
- Compare l'IP du client à celle du DNS des domaines enregistrés
- Vérifie la validité de la récompense (non supprimée, activée, contrat signé et avec la catégorie "En ligne")
- Vérifie si la récompense peut s'appliquer à un utilisateur, une entreprise ou une association (B2B / B2C)
- Valide la récompense pour l'utilisateur, l'entreprise ou l'association
- Consommer les codes correspondants pour indiquer qu'ils ont été utilisés
- Supprime le token

**ERROR** : { success: false, message: "[REWARD] ..." }<br/>
**SUCCESS** : { success: true, message: "..." }