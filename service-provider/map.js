function initMap() {
  // Simulated technician and customer locations
  const technicianLocation = { lat: 23.780573, lng: 90.279239 }; // Example: Mirpur, Dhaka
  const customerLocation = { lat: 23.746466, lng: 90.376015 };   // Example: Dhanmondi, Dhaka

  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 13,
    center: technicianLocation,
  });

  const technicianMarker = new google.maps.Marker({
    position: technicianLocation,
    map: map,
    title: "Technician Location",
    icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
  });

  const customerMarker = new google.maps.Marker({
    position: customerLocation,
    map: map,
    title: "Customer Location",
    icon: "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
  });

  // Route drawing
  const directionsService = new google.maps.DirectionsService();
  const directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

  directionsService.route(
    {
      origin: technicianLocation,
      destination: customerLocation,
      travelMode: google.maps.TravelMode.DRIVING,
    },
    (response, status) => {
      if (status === "OK") {
        directionsRenderer.setDirections(response);
      } else {
        alert("Could not display directions due to: " + status);
      }
    }
  );
}
