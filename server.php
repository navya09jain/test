<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "kalam_test";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Die if the connection was not successful
if (!$conn) {
    die("Sorry, we failed to connect: " . mysqli_connect_error());
}

// Parameters sent by DataTables
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;
$search = isset($_GET['search']['value']) ? mysqli_real_escape_string($conn, $_GET['search']['value']) : '';

// Fetch data from crm_lead_master_data table with server-side processing
// Fetch data from crm_lead_master_data table with server-side processing and search functionality
$query = "SELECT crm_lead_master_data.*, crm_calling_status.DOR AS Summary_DOR
          FROM crm_lead_master_data
          LEFT JOIN crm_calling_status ON crm_lead_master_data.Caller_ID = crm_calling_status.Caller_ID
          WHERE 
            Mobile LIKE '%$search%' OR
            Alternate_Mobile LIKE '%$search%' OR
            Whatsapp LIKE '%$search%' OR
            Email LIKE '%$search%' OR
            State LIKE '%$search%' OR
            City LIKE '%$search%' OR
            DOR LIKE '%$search%'
          LIMIT $start, $length";


// Execute the query
$result = mysqli_query($conn, $query);


// Check if query was successful
if ($result) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $row['Combined'] = $row['Mobile'] . '<br>' . $row['Alternate_Mobile'] . '<br>' . $row['Whatsapp'] . '<br>' . $row['Email'];
        $row['Combined2'] = $row['State'] . '<br>' . $row['City'];
        $data[] = $row;
    }

    // Get the total number of records without filtering
    $totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM crm_lead_master_data"));

    // Output JSON response
    echo json_encode(array(
        'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords, // Assuming no filtering for now
        'data' => $data,
    ));
} else {
    echo json_encode(array('error' => "Error: " . $query . "<br>" . mysqli_error($conn)));
}

// Close the connection
mysqli_close($conn);
