
{{-- resources/views/partials/top-bar.blade.php --}}
<div class="top-bar d-flex sticky-top bg-light align-items-center justify-content-between px-3 py-2 px-md-3 py-md-3 border-bottom shadow-sm">
    
    {{-- Stats Button; --}}
    {{-- Nur für Leitung sichtbar, sonst leeren Platz für Ausrichtung. --}}
 <div class="ms-3 me-3">
    @if(auth()->user()->role === 'leitung')
        @php($isActive = request()->routeIs('stats'))
        <a href="{{ route('stats') }}"
           class="btn btn-sm fw-bold rounded-pill d-flex align-items-center gap-1 px-2 px-md-3 py-1 py-md-2
           {{ $isActive ? 'btn-primary text-white' : 'btn-outline-primary' }}"
           style="font-size: 0.85rem;">
            <i class="bi bi-pie-chart-fill"></i>
            <span class="d-sm-inline">Statistiken</span>
        </a>
    @else
        <div style="width: 110px;"></div>
    @endif
</div>


    {{-- Notification button + dropdown + cards --}}
<div id="notification-area" class="dropdown position-relative">
    <a class="btn btn-outline-primary rounded-pill d-flex align-items-center gap-1 px-2 px-md-3 py-1 py-md-2 ms-3 me-3 dropdown-toggle"
       href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill"></i>
        <span id="notification-count" class="badge bg-danger d-none">0</span>
    </a>

    <!-- Dropdown-Menü für alle Benachrichtigungen -->
    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2"
        aria-labelledby="notificationDropdown"
        id="notification-list">
    </ul>

    <!-- Pop-up-Boxen für neue/ungelesene Benachrichtigungen -->
    <div id="live-notification-boxes"
         class="position-absolute end-0 mt-2"
         style="min-width: 450px; max-width: 70vw;">
    </div>
</div>


</div>

<script>
    const dismissedNotifications = new Set(JSON.parse(localStorage.getItem('dismissedNotis') || '[]')); // geschlossenen Meldungen vom lokalen Browser-Speicher

   function loadFahrzeugNotifications() {
       fetch('{{ route('notifications.fahrzeuge') }}')
           .then(response => response.json())
           .then(data => {
               const list = document.getElementById('notification-list'); //Dropdown
               const count = document.getElementById('notification-count'); // Badge für Anzahl der Benachrichtigungen

               list.innerHTML = ''; // clear previous items

               if (data.notifications.length > 0) {
                   count.classList.remove('d-none'); //badge anzeigen
                   count.innerText = data.notifications.length; // Anzahl setzen

                 data.notifications.forEach((noti, index) => {
                const notiId = btoa(noti); // eindeutiger ID für lokale Speicherung

    // Wenn diese Nachricht noch nicht geschlossen wurde, box anzeigen
    if (!dismissedNotifications.has(notiId)) {

     showLiveNotificationBox(noti, notiId);
    }


    // Nachricht ins Dropdown einfügen (immer)
    const li = document.createElement('li'); // Dropdown-Eintrag erstellen
    li.innerHTML = `
        <a href="#"
           class="dropdown-item d-flex justify-content-between align-items-center px-3 py-2 text-dark text-decoration-none">
            <span class="text-wrap lh-sm">${noti}</span>
            <i class="bi bi-chevron-right small text-muted"></i>
        </a>
        ${index !== data.notifications.length - 1 ? '<hr class="my-1 mx-2">' : ''} 
    `;
    list.appendChild(li); // Dropdown-Eintrag hinzufügen
});



               } else { // Keine Benachrichtigungen vorhanden
                   count.classList.add('d-none');  // Badge ausblenden
                   const li = document.createElement('li');
                   li.innerHTML = `<span class="dropdown-item text-success small"> Keine Meldungen zurzeit</span>`;
                   list.appendChild(li);
               }
           })
   }




function showLiveNotificationBox(text, notiId) {
    const container = document.getElementById('live-notification-boxes');

    // keine doppelte Boxen
    if (document.getElementById(`notif-${notiId}`)) return; 

    // neue Box erstellen
    const box = document.createElement('div'); 
    box.id = `notif-${notiId}`; // eindeutige ID
    box.className = 'card shadow-lg position-relative mt-2';


    // Box-Inhalt
    box.innerHTML = `
        <div class="card-body py-2 px-3 small">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close"></button>
            ${text}
        </div>
    `;

    // Schließen-Button hinzufügen
    box.querySelector('.btn-close').addEventListener('click', () => {
        container.removeChild(box); // Box entfernen
        dismissedNotifications.add(notiId); // noti-ID zur Liste hinzufügen
        localStorage.setItem('dismissedNotis', JSON.stringify(Array.from(dismissedNotifications))); // im Browser speichern
    });

    container.appendChild(box); // Box zum Container hinzufügen (ANZEIGEN)

}

    // Benachrichtigungen laden
    document.addEventListener('DOMContentLoaded', function () {
       loadFahrzeugNotifications(); // Lade beim Start
       setInterval(() => {
           loadFahrzeugNotifications();
       }, 3000); // Refresh every 3 seconds
   });

    // Benachrichtigungen-boxes nach 5 Minuten wieder anzeigen
    setInterval(() => {
    localStorage.removeItem('dismissedNotis'); // Browser-Speicher leeren
    dismissedNotifications.clear(); // leere Liste
}, 300000); // 5 minutes


</script>


<style>
#live-notification-boxes .card {
    word-break: break-word;
    overflow-wrap: break-word;
    border-radius: 0.5rem;
    padding: 1rem 0.5rem;
    background-color: #f8f9fa;
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.15);
    border-radius: 0.5rem;
    border-left: 4px solid #fd0d0dff;

}

#notification-list {
  width: 97vw;
  max-height: 400px;
  overflow-y: auto;
  border-radius: 0.5rem;
}

@media (min-width: 768px) {
  #notification-list {
    min-width: 500px;
    width: auto;
  }
}
    </style>