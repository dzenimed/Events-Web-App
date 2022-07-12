class Events {
  static init() {
    //    Events.getEvents();

    $("#collapse-trigger").click(function(e) {
      console.log("success");
      e.preventDefault();
      $("#add-event-collapse").collapse('toggle');
    });

    $("#add-event-form").validate({
      rules: {
        name: "required",
        status: "required",
        city: "required",
        address: "required",
        date_held: "required",
        num_of_tickets: "required",
        description: "required",
        type_name: "required",
        company_name: "required",
      },
      submitHandler: function(form, event) {
        console.log("success2");
        event.preventDefault();
        const Event = Utility.jsonize_form("#add-event-form");
        console.log(Event)
        $('#overlay').fadeIn();
        Events.addEvent(Event);
      }
    });


  }
  static addEvent(Event) {
    RestClient.post("rest/admin/add/event", Event, function(data) {
      toastr.success("New Event has been added!");
      $("#add-event-form").trigger("reset");
      //    $("#cars-insert").empty();
      $('#overlay').fadeOut();
      //    Events.getEvents();
    })
  }
}
