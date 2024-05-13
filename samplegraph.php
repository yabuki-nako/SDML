<?php
// Include the database credentials file
require_once "config.php";

// Query to get data for chart 1 (Department-wise bookings count)
$sql1 = "        SELECT specialties.sname AS department, COUNT(*) AS bookings_count 
FROM appointments
INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id 
WHERE App_status = 'done'
GROUP BY specialties.sname;";

$result1 = $mysqli->query($sql1);

$data1 = array();
while ($row1 = $result1->fetch_assoc()) {
    $data1[] = $row1;
}

// Query to count male and female patients
$sql2 = "SELECT pGender, COUNT(*) AS patient_count
        FROM patient_detail
        GROUP BY pGender";

$result2 = $mysqli->query($sql2);

$data2 = array();
while ($row2 = $result2->fetch_assoc()) {
    $data2[] = $row2;
}

$sql3 = "SELECT MONTH(appDate) AS month_number, COUNT(*) AS total_appointments
FROM appointments WHERE App_status = 'done'
GROUP BY MONTH(appDate)";

$result3 = $mysqli->query($sql3);

$data3 = array();
while ($row3 = $result3->fetch_assoc()) {
    $data3[] = $row3;
}

$sql4 = "SELECT YEAR(appDate) AS appointment_year, COUNT(*) AS total_appointments
        FROM appointments WHERE App_status = 'done'
        GROUP BY YEAR(appDate)";

$result4 = $mysqli->query($sql4);

$data4 = array();
while ($row4 = $result4->fetch_assoc()) {
    $data4[] = $row4;
}

// Query to get data for chart 5 (Total appointments by department per month)
$sql5 = "SELECT specialties.sname AS department, 
MONTH(appointments.appDate) AS month_number, 
COUNT(*) AS bookings_count
FROM appointments 
INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id WHERE App_status = 'done'
GROUP BY specialties.sname, MONTH(appointments.appDate)";

$result5 = $mysqli->query($sql5);

$data5 = array();
while ($row5 = $result5->fetch_assoc()) {
    $data5[] = $row5;
}
    $sql6 = " SELECT 
    specialties.sname AS department, 
    MONTH(appointments.appDate) AS month_number, 
    YEAR(appointments.appDate) AS appointment_year,
    COUNT(*) AS bookings_count FROM  appointments
    INNER JOIN doctor ON appointments.docid = doctor.docid
    INNER JOIN specialties ON doctor.specialties = specialties.id WHERE App_status = 'done'
    GROUP BY specialties.sname, MONTH(appointments.appDate), YEAR(appointments.appDate)";

$result6 = $mysqli->query($sql6);

$data6 = array();
while ($row6 = $result6->fetch_assoc()) {
    $data6[] = $row6;
}
// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics Dashboard</title>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

</head>
<body>
<div id="chart1" style="width: 50%; height: 300px; float: left;"></div>
<div id="chart2" style="width: 50%; height: 300px; float: left;"></div>
<div id="chart3" style="width: 50%; height: 300px; float: left;"></div>
<div id="chart4" style="width: 50%; height: 300px; float: left;"></div>
<div id="chart5" style="width: 50%; height: 300px; float: left;"></div>
<div id="chart6" style="width: 50%; height: 300px; float: left;"></div>
<div id="yearDropdown"></div><i>drop down for chart 6</div>


<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    // Draw Chart 1: Bookings by Department (Pie Chart)
    var data1 = <?php echo json_encode($data1); ?>;
    var dataTable1 = new google.visualization.DataTable();
    dataTable1.addColumn('string', 'Department');
    dataTable1.addColumn('number', 'Bookings Count');
    for (var i = 0; i < data1.length; i++) {
        var row1 = [data1[i].department, parseInt(data1[i].bookings_count)];
        dataTable1.addRow(row1);
    }
    var options1 = {
        title: 'Bookings by Department'
    };
    var chart1 = new google.visualization.PieChart(document.getElementById('chart1'));
    chart1.draw(dataTable1, options1);

    // Draw Chart 2: Number of Patients by Gender (Bar Chart)
    var data2 = <?php echo json_encode($data2); ?>;
    var dataTable2 = new google.visualization.DataTable();
    dataTable2.addColumn('string', 'Gender');
    dataTable2.addColumn('number', 'Number of Patients');
    for (var j = 0; j < data2.length; j++) {
        var gender = data2[j].pGender === 'Male' ? 'Male' : 'Female';
        var count = parseInt(data2[j].patient_count);
        var row2 = [gender, count];
        dataTable2.addRow(row2);
    }
    var options2 = {
        title: 'Number of Patients by Gender',
        bars: 'horizontal'
    };
    var chart2 = new google.visualization.BarChart(document.getElementById('chart2'));
    chart2.draw(dataTable2, options2);
    
    // Draw Chart 3: Value of Services Availed per Month (Line Graph)
    var data3 = <?php echo json_encode($data3); ?>;
    var dataTable3 = new google.visualization.DataTable();
    dataTable3.addColumn('string', 'Month');
    dataTable3.addColumn('number', 'Total Service Value');
    
    // Month name array
    var monthNames = ["January", "February", "March", "April", "May", "June",
                      "July", "August", "September", "October", "November", "December"];
    
    for (var k = 0; k < data3.length; k++) {
        var monthNumber = parseInt(data3[k].month_number);
        var monthName = monthNames[monthNumber - 1]; // Adjust index to start from 0
        var value = parseFloat(data3[k].total_appointments);
        var row3 = [monthName, value];
        dataTable3.addRow(row3);
    }
    var options3 = {
        title: 'Total costumer per month',
        curveType: 'function',
        legend: { position: 'bottom' }
    };
    var chart3 = new google.visualization.LineChart(document.getElementById('chart3'));
    chart3.draw(dataTable3, options3);
    //
    var data4 = <?php echo json_encode($data4); ?>;
    var dataTable4 = new google.visualization.DataTable();
    dataTable4.addColumn('string', 'Year');
    dataTable4.addColumn('number', 'Total Service Value');
    
    for (var i = 0; i < data4.length; i++) {
        var year = data4[i].appointment_year;
        var value = parseFloat(data4[i].total_appointments);
        var row4 = [year, value];
        dataTable4.addRow(row4);
    }
    var options4 = {
        title: 'Total costumer per year',
        curveType: 'function',
        legend: { position: 'bottom' }
    };
    var chart4 = new google.visualization.LineChart(document.getElementById('chart4'));
    chart4.draw(dataTable4, options4);
    //
// Draw Chart 5: Total Appointments by Department per Month (Multi-Line Chart)
var data5 = <?php echo json_encode($data5); ?>;
var dataTable5 = new google.visualization.DataTable();
dataTable5.addColumn('string', 'Month');

// Add columns for each department dynamically
var departments = [];
for (var m = 0; m < data5.length; m++) {
    if (!departments.includes(data5[m].department)) {
        departments.push(data5[m].department);
        dataTable5.addColumn('number', data5[m].department);
    }
}

// Add rows
var rows = {};
for (var n = 0; n < data5.length; n++) {
    var monthNumber5 = parseInt(data5[n].month_number);
    var monthName5 = monthNames[monthNumber5 - 1]; // Adjust index to start from 0
    if (!rows[monthName5]) {
        rows[monthName5] = Array(departments.length + 1).fill(0); // Add 1 for the 'Month' column
        rows[monthName5][0] = monthName5;
    }
    var departmentIndex5 = departments.indexOf(data5[n].department) + 1;
    rows[monthName5][departmentIndex5] = parseInt(data5[n].bookings_count);
}

// Convert rows object to array and add to DataTable
for (var month5 in rows) {
    dataTable5.addRow(rows[month5]);
}

// Draw the chart
var options5 = {
    title: 'Total Appointments by Department per Month',
    curveType: 'function',
    legend: { position: 'bottom' }
};
var chart5 = new google.visualization.LineChart(document.getElementById('chart5'));
chart5.draw(dataTable5, options5);


    // Function to update chart based on selected departments
    // Function to update chart based on selected departments for Chart 5
   
    //--end--//
    var data6 = <?php echo json_encode($data6); ?>;
    var dataTable6 = new google.visualization.DataTable();
    dataTable6.addColumn('string', 'Month');

    // Extract unique departments
    var departments = [...new Set(data6.map(item => item.department))];

    // Add columns for each department
    departments.forEach(department => {
        dataTable6.addColumn('number', department);
    });

    // Create rows
    var rows = [];
    var monthNames = ["January", "February", "March", "April", "May", "June",
                      "July", "August", "September", "October", "November", "December"];

    monthNames.forEach(month => {
        var rowData = [month];
        departments.forEach(department => {
            var count = 0;
            var matchingData = data6.find(item => item.department === department && monthNames[item.month_number - 1] === month);
            if (matchingData) {
                count = parseInt(matchingData.bookings_count);
            }
            rowData.push(count);
        });
        rows.push(rowData);
    });

    // Add rows to DataTable
    dataTable6.addRows(rows);

    // Set chart options
    var options6 = {
        title: 'Total Appointments by Department per Month (Stacked Column Chart)',
        isStacked: true,
        legend: { position: 'bottom' }
    };

    // Draw the chart
    var chart6 = new google.visualization.ColumnChart(document.getElementById('chart6'));
    chart6.draw(dataTable6, options6);

    function updateChart(data, selectedYear) {
    var dataTable6 = new google.visualization.DataTable();
    dataTable6.addColumn('string', 'Month');

    // Extract unique departments
    var departments = [...new Set(data.map(item => item.department))];

    // Add columns for each department
    departments.forEach(department => {
        dataTable6.addColumn('number', department);
    });

    // Filter data based on selected year
    var filteredData = data.filter(function(item) {
        return item.appointment_year == selectedYear;
    });

    // Create rows
    var rows = [];
    var monthNames = ["January", "February", "March", "April", "May", "June",
                      "July", "August", "September", "October", "November", "December"];

    monthNames.forEach(month => {
        var rowData = [month];
        departments.forEach(department => {
            var count = 0;
            var matchingData = filteredData.find(item => item.department === department && monthNames[item.month_number - 1] === month);
            if (matchingData) {
                count = parseInt(matchingData.bookings_count);
            }
            rowData.push(count);
        });
        rows.push(rowData);
    });

    // Add rows to DataTable
    dataTable6.addRows(rows);

    // Set chart options
    var options6 = {
        title: 'Total Appointments by Department per Month (Stacked Column Chart)',
        isStacked: true,
        legend: { position: 'bottom' }
    };

    // Draw the chart
    var chart6 = new google.visualization.ColumnChart(document.getElementById('chart6'));
    chart6.draw(dataTable6, options6);
}

// Dropdown menu for selecting the year
var yearDropdown = document.getElementById('yearDropdown');
    var currentYear = new Date().getFullYear(); // Get the current year
    var selectHTML = '<select id="yearSelect">';
    for (var year = currentYear; year >= 2023; year--) { // Loop from current year to 2023
        selectHTML += '<option value="' + year + '">' + year + '</option>';
    }
    selectHTML += '</select>';

    yearDropdown.innerHTML = selectHTML;

    // Add event listener to the year dropdown
    document.getElementById('yearSelect').addEventListener('change', function() {
        var selectedYear = this.value; // Get the selected year
        updateChart(data6, selectedYear); // Call the updateChart function with selected year
    });
}

function updateChart(data, selectedYear) {
    // Update chart logic...
}
</script>
</body>
</html>