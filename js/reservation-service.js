var ReservationService = {
  list_reservations: function(entity) {
    $.ajax({
      url: '/user/reservations',
      type: 'GET',
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success:function(data){
        data.forEach(element => {
            var row = document.createElement("tr");
            var name = document.createElement('td');
            var surname = document.createElement('td');
            var event = document.createElement('td');
            var city = document.createElement('td');
            var address = document.createElement('td');
            var date_held = document.createElement('td');
            var date_reserved = document.createElement('td');
            var status = document.createElement('td');

            name.innerHTML = element.Name;
            surname.innerHTML = element.Surname;
            event.innerHTML = element.Event;
            city.innerHTML = element.City;
            address.innerHTML = element.Address;
            date_held.innerHTML = element.DateHeld;
            date_reserved.innerHTML = element.DateReserved;
            status.innerHTML = element.Status;

            row.appendChild(name);
            row.appendChild(surname);
            row.appendChild(event);
            row.appendChild(city);
            row.appendChild(address);
            row.appendChild(date_held);
            row.appendChild(date_reserved);
            row.appendChild(status);

            $('#table').append(row);
        });
    },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  }
}
