$(document).ready(function () {
  $("#crmDataTable").DataTable({
    paging: true,
    ordering: true,
    info: true,
    searching: true,
    serverSide: true,
    lengthMenu: [10, 50, 100], // Define the options for entries per page
    pageLength: 10, // Set the default number of entries per page
    ajax: {
      url: "server.php",
      type: "GET",
      error: function (xhr, error, thrown) {
        console.log("DataTables error:", error);
      },
    }, // Point to your server-side script
    columns: [
      { data: "Lead_ID", title: "Lead_ID" },
      { data: "Name", title: "Name" },
      { data: "Combined", title: "Mobile/Alternate/Whatsapp/Email" },
      { data: "Combined2", title: "State/City" },
      { data: "Interested_In", title: "Interested_In" },
      { data: "Source", title: "Source" },
      { data: "Status", title: "Status" },
      { data: "DOR", title: "DOR" }, // Assuming "DOR" is from "crm_lead_master_data" table
      { data: "Summary_DOR", title: "Summary DOR" }, // Assuming "DOR" is from "crm_calling_status" table
      {
        // Option button column
        data: null,
        title: "Option",
        render: function (data, type, row) {
          return (
            "<div>" +
            '<button onclick="editRow(' +
            row.Lead_ID +
            ')">Edit</button>' +
            '<button onclick="getStatus(' +
            row.Lead_ID +
            ')">Status</button>' +
            "</div>"
          );
        },
      },
      { data: "Name", title: "Caller" },
    ],
  });
});
