# Plug-In Jeedom OpenMQTTGateway (openmqttgateway)

Ce plugin permet de gérer les objets BLE découvert par une passerelle de type OpenMQTTGateway (https://docs.openmqttgateway.com/)



---
## Aspects Techniques

### Multi-Gateways

Lorsque le réseau possède plusieurs Gateways et qu'un objet est vu par plusieurs d'entre elles, alors le plugin ne prend en considération les nouvelles mesures uniquement si la puissance du signal reçu est meilleur avec la nouvelle gateway.

### Change Logs

Release v0.1 (beta) :
 - Première version
 - Ajout d'une commande (info) 'online_status' qui permet d'indiquer si la gateway envoi toujours des messages MQTT. Le temps d'absence de message qui déclenche le mode offline est par défaut de 2 minutes, mais peut se configurer dans les paramètres de la gateway. 
 - Ajout de la détection du statut online/offline de la gateway par analyse des messages LWT=online/offline.


