// CODE A IMPLEMENTER
const son = new Audio("meeeh.mp3");
const chevre = document.getElementById("goat");
let activerson=true;
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then((registration) => {
        console.log('Service Worker enregistré avec succès, scope :', registration.scope);
      })
      .catch((error) => {
        console.log('Échec de l\'enregistrement du Service Worker :', error);
      });
  });
}
// Quand on clique sur l'image de la chèvre elle fait le son et l'animation
function lancer()
{
    chevre.src = "goat_meh.jpg"
    chevre.classList.remove("anime");
    void chevre.offsetWidth;
    chevre.classList.add("anime");
    son.play();
}
chevre.addEventListener("animationend",()=>{
    chevre.src = "goat.jpg";
})
// Attraper les événements de changement d'orientation, si l'angle change et 
// que le téléphone est à l'enver alors faire comme si on clique sur la chèvre

 window.addEventListener('deviceorientation', function(eventData) {
        if((eventData.beta <= -150 || eventData.beta >= 150) && activerson)
        {
            activerson = false;
            lancer();
        }
        if((eventData.beta >= -30 && eventData.beta <= 30) && !activerson)
        {
            activerson = true;
        }

    }, false);