# Plug-In Jeedom OpenMQTTGateway (openmqttgateway)

Ce plugin permet de g�rer les objets BLE d�couvert par une passerelle de type OpenMQTTGateway (https://docs.openmqttgateway.com/)



---
## Aspects Techniques

### Multi-Gateways

Lorsque le r�seau poss�de plusieurs Gateways et qu'un objet est vu par plusieurs d'entre elles, alors le plugin ne prend en consid�ration les nouvelles mesures uniquement si la puissance du signal re�u est meilleur avec la nouvelle gateway.

### Change Logs

Release v0.1 (beta) :
 - Premi�re version
 - Ajout d'une commande (info) 'online_status' qui permet d'indiquer si la gateway envoi toujours des messages MQTT. Le temps d'absence de message qui d�clenche le mode offline est par d�faut de 2 minutes, mais peut se configurer dans les param�tres de la gateway. 
 - Ajout de la d�tection du statut online/offline de la gateway par analyse des messages LWT=online/offline.


