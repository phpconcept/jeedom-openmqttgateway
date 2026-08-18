# Plug-In Jeedom OpenMQTTGateway (openmqttgateway)

Ce plugin permet de gérer les objets BLE découvert par une passerelle de type OpenMQTTGateway (https://docs.openmqttgateway.com/)



---
## Aspects Techniques

### Multi-Gateways

Lorsque le réseau possède plusieurs Gateways et qu'un objet est vu par plusieurs d'entre elles, alors le plugin ne prend en considération les nouvelles mesures uniquement si la puissance du signal reçu est meilleur avec la nouvelle gateway (par défaut d'au moins 5dBm). Ou que la gateway de référence n'a plus communiqué pendant un temps donné (par défaut de 60 secondes).
Ces paramètres du cycle d'hystérésis sont configurables dans les paramètres du plugin.

### Change Logs

Release v0.2.0 (dev) :
 - Ajout d'un tableau de bord (dashboard) : nouvelle page listant toutes les gateways sous forme de widgets dans une grille, accessible via un nouveau bouton «Tableau de bord» depuis la page de gestion du plugin.
 - Widget gateway : affichage du statut en ligne/hors ligne, de la date du dernier message MQTT reçu, et du nombre d'objets BLE rattachés (en ligne / total).
 - Affichage de l'adresse IP de la gateway dans le widget (valeur issue d'une commande existante).
 - Ajout d'un test de disponibilité HTTP de secours (cron toutes les 5 minutes) : lorsque la gateway est inactive en MQTT, vérification qu'elle répond toujours en HTTP, avec contrôle de l'authentification (Digest) et du realm attendu, afin d'éviter une confusion si l'IP a été reprise par un autre équipement.
 - Ajout d'une icône de statut HTTP dans le widget, à côté de l'adresse IP : coche verte («OpenMQTT joignable en IP») ou triangle orange («OpenMQTT injoignable en IP»), avec info-bulle au survol.

Release v0.1 (beta) :
 - Première version
 - Ajout d'une commande (info) 'online_status' qui permet d'indiquer si la gateway envoi toujours des messages MQTT. Le temps d'absence de message qui déclenche le mode offline est par défaut de 2 minutes, mais peut se configurer dans les paramètres de la gateway. 
 - Ajout de la détection du statut online/offline de la gateway par analyse des messages LWT=online/offline.


