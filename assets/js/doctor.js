  // Function to validate the form before submission
  function validateForm() {
    // Get the value of the textarea
    var note = document.getElementById('w3review').value.trim();

    // Check if the textarea is empty
    if (note === '') {
      // Display an error message
      alert('Please provide a note.');
      return false; // Prevent form submission
    }
    return true; // Proceed with form submission if validation passes
  }

  function openModal1(appointmentID1) {
    $('#app_idModal1').val(appointmentID1); // Set the value of Appointment ID textbox
    $.ajax({
      type: 'POST',
      url: 'fetch_files.php',
      data: { appointmentID: appointmentID1 },
      success: function(response) {
        $('#fileupload').modal('show'); // Open the modal
        $('#fileupload .modal-body').html(response); // Update the modal body with the response from the server
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  }


  function calculateBMI() {
    // Get the height value in meters
    var height_cm = parseFloat(document.getElementById("pHeight_Modal").value);
    var height_m = height_cm / 100; // Convert cm to m

    // Get the weight value in kilograms
    var weight = parseFloat(document.getElementById("pWeight_Modal").value);

    // Check if height and weight are valid numbers
    if (!isNaN(height_m) && !isNaN(weight)) {
        // Calculate BMI
        var bmi = weight / (height_m * height_m);

        // Update the value of bmi_Modal input field
        document.getElementById("bmi_Modal").value = bmi.toFixed(2); // Round BMI to 2 decimal places
    } else {
        // If height or weight is not a valid number, clear the BMI input field
        document.getElementById("bmi_Modal").value = "";
    }
}
//   pHeight_Modal.oninput = showvalue();

//   function showvalue(){
//     pWeight_Modal.value = this.value;
//   }
//   document.getElementById("pHeight_Modal").addEventListener("input", calculateBMI);
// document.getElementById("pWeight_Modal").addEventListener("input", calculateBMI);

// function calculateBMI() {
//     // Get the height value in meters
//     var height_cm = parseFloat(document.getElementById("pHeight_Modal").value);
//     var height_m = height_cm / 100; // Convert cm to m

//     // Get the weight value in kilograms
//     var weight = parseFloat(document.getElementById("pWeight_Modal").value);

//     // Check if height and weight are valid numbers
//     if (!isNaN(height_m) && !isNaN(weight)) {
//         // Calculate BMI
//         var bmi = weight / (height_m * height_m);

//         // Update the value of bmi_Modal input field
//         document.getElementById("bmi_Modal").value = bmi.toFixed(2); // Round BMI to 2 decimal places
//     } else {
//         // If height or weight is not a valid number, clear the BMI input field
//         document.getElementById("bmi_Modal").value = "";
//     }
// }