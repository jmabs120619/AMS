<?php
// index.php
include 'admin/loading_screen.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>RFID Attendance Monitoring System</h1>
        <form id="attendanceForm">
            <div class="mb-3">
                <label for="rfid_uid" class="form-label">Scan RFID Tag:</label>
                <input type="text" id="rfid_uid" name="rfid_uid" class="form-control" autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div id="message" class="mt-3"></div>

        <div id="logsTable" class="mt-4">
            <h3>Your Attendance Logs</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In AM</th>
                        <th>Time Out AM</th>
                        <th>Time In PM</th>
                        <th>Time Out PM</th>
                    </tr>
                </thead>
                <tbody id="logsBody">
                    <!-- Logs will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
        $("#attendanceForm").on("submit", function(e) {
            e.preventDefault();
            const rfid_uid = $("#rfid_uid").val();

            $.post("log_attendance.php", {
                rfid_uid
            }, function(data) {
                const response = JSON.parse(data);

                $("#message").html(
                    `<div class="alert alert-${response.status === 'success' ? 'success' : 'danger'}">${response.message}</div>`
                );

                if (response.status === 'success') {
                    $("#rfid_uid").val("");

                    const logsBody = $("#logsBody");
                    logsBody.empty();

                    const groupedLogs = {};
                    response.logs.forEach((log) => {
                        const logDate = log.date;
                        if (!groupedLogs[logDate]) {
                            groupedLogs[logDate] = {
                                time_in_am: "-",
                                time_out_am: "-",
                                time_in_pm: "-",
                                time_out_pm: "-",
                            };
                        }
                        if (log.session === "morning") {
                            groupedLogs[logDate][log.log_type === "time_in" ? "time_in_am" : "time_out_am"] = log.time;
                        } else if (log.session === "afternoon") {
                            groupedLogs[logDate][log.log_type === "time_in" ? "time_in_pm" : "time_out_pm"] = log.time;
                        }
                    });

                    Object.keys(groupedLogs).forEach((date) => {
                        const row = groupedLogs[date];
                        logsBody.append(
                            `<tr>
                                <td>${date}</td>
                                <td>${row.time_in_am}</td>
                                <td>${row.time_out_am}</td>
                                <td>${row.time_in_pm}</td>
                                <td>${row.time_out_pm}</td>
                            </tr>`
                        );
                    });
                }
            });

        });
    </script> -->

    <script>
        // Submit the attendance form using AJAX
        $("#attendanceForm").on("submit", function(e) {
            e.preventDefault(); // Prevent default form submission
            const rfid_uid = $("#rfid_uid").val();

            // Send the RFID UID to the server via AJAX
            $.ajax({
                url: "log_attendance.php", // The server-side PHP script to handle the request
                method: "POST",
                data: { rfid_uid: rfid_uid }, // Data to send (RFID UID)
                success: function(data) {
                    // Parse the JSON response from the server
                    const response = JSON.parse(data);

                    // Display success or error message
                    $("#message").html(
                        `<div class="alert alert-${response.status === 'success' ? 'success' : 'danger'}">${response.message}</div>`
                    );

                    // If the attendance log is successfully updated, clear input and reload the logs
                    if (response.status === 'success') {
                        $("#rfid_uid").val(""); // Clear the RFID UID input field

                        // Clear the existing logs
                        const logsBody = $("#logsBody");
                        logsBody.empty();

                        // Group the logs by date for better display
                        const groupedLogs = {};
                        response.logs.forEach((log) => {
                            const logDate = log.date;
                            if (!groupedLogs[logDate]) {
                                groupedLogs[logDate] = {
                                    time_in_am: "-",
                                    time_out_am: "-",
                                    time_in_pm: "-",
                                    time_out_pm: "-",
                                };
                            }
                            if (log.session === "morning") {
                                groupedLogs[logDate][log.log_type === "time_in" ? "time_in_am" : "time_out_am"] = log.time;
                            } else if (log.session === "afternoon") {
                                groupedLogs[logDate][log.log_type === "time_in" ? "time_in_pm" : "time_out_pm"] = log.time;
                            }
                        });

                        // Populate the logs table with grouped logs
                        Object.keys(groupedLogs).forEach((date) => {
                            const row = groupedLogs[date];
                            logsBody.append(
                                `<tr>
                                    <td>${date}</td>
                                    <td>${row.time_in_am}</td>
                                    <td>${row.time_out_am}</td>
                                    <td>${row.time_in_pm}</td>
                                    <td>${row.time_out_pm}</td>
                                </tr>`
                            );
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX request failure
                    $("#message").html(`<div class="alert alert-danger">An error occurred: ${error}</div>`);
                }
                
            });
            console.log(response);
        });
    </script>

</body>

</html>