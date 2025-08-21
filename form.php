<?php
session_start();
include 'connect.php';
date_default_timezone_set("Asia/Kuala_Lumpur");

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

$staffId = $_SESSION['staff_id'];
$query = "SELECT staff_name, staff_email, staff_dept, costcentre FROM staff WHERE staff_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $staffId);
$stmt->execute();
$stmt->bind_result($staff_name, $email, $staff_dept, $costcentre);
$stmt->fetch();
$stmt->close();

$_SESSION['staff_name'] = $staff_name;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_name = $conn->real_escape_string($_POST['full_name']);
    $submitted_staff_id = $conn->real_escape_string($_POST['staffID']);
    $email = $conn->real_escape_string($_POST['email']);
    $costcentre = $conn->real_escape_string($_POST['costcentre']);
    $tel_office = $conn->real_escape_string($_POST['telephone_office']);
    $staff_dept = $conn->real_escape_string($_POST['staff_dept']);
    $access_role = $conn->real_escape_string($_POST['access_role']);
    $approver = $conn->real_escape_string($_POST['approver']);
    $terms = isset($_POST['terms']) ? 1 : 0;

    $acc_seq = '';
    $usr_id = '';
    $request_date = date('Y-m-d'); // remove the hour
    $requestor = $_SESSION['staff_name'];
    $date_approve = null;
    $date_close = null;
    $helpdesk_rem = '';
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO account_request (acc_seq, staffID, request_date, staff_name, usr_id, costcentre, tel_office, staff_dept, email, access_role, approver, requestor, date_approve, date_close, helpdesk_rem, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssss", $acc_seq, $submitted_staff_id, $request_date, $staff_name, $usr_id, $costcentre, $tel_office, $staff_dept, $email, $access_role, $approver, $requestor, $date_approve, $date_close, $helpdesk_rem, $status);


    if ($stmt->execute()) {
        $_SESSION['success'] = 1;
    } else {
        $_SESSION['err'] = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="images/circlelns.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TOMMS Application Form</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">
    <main class="bg-primary">
        <div class="container">
            <div class="row justify-content-center">
                <?php if (isset($_SESSION['success']) && $_SESSION['success'] == 1) { ?>
                    <div class="alert alert-success alert-dismissible animated fadeInUp" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <strong>Successfully registered!</strong> Your registration has been successfully submitted and awaiting approval.
                    </div>
                    <?php unset($_SESSION['success']);
                } ?>
                <?php if (isset($_SESSION['err']) && $_SESSION['err'] != '') { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Fail to Process!</strong> <?php echo $_SESSION['err']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['err']);
                } ?>
                <div class="col-md-10">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3>TOMMS ACCOUNT ACCESS REQUEST APPLICATION</h3>
                        </div>
                        <div class="container">
                            <div class="card-body">
                                <form role="form" action="../fyp/submit_form.php" method="POST">
                                    <div class="form-group full-name-group">
                                        <div class="col-md-12">
                                            <label for="full_name">Full Name (As in I/C)</label>
                                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($staff_name); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group">
                                        <label class="small mb-1" for="username">Domain Username </label>
                                        <input class="form-control py-1" id="username" name="username" value="root" readonly />
                                    </div>
                                        <div class="col-md-3 form-group">
                                            <label class="small mb-1" for="staffID">Staff ID</label>
                                            <input class="form-control py-1" id="staffID" name="staffID" value="<?php echo htmlspecialchars($staffId); ?>" />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="small mb-1" for="costcentre">Cost Centre</label>
                                            <input list="CClist" class="form-control py-1" id="costcentre" name="costcentre" value="<?php echo htmlspecialchars($costcentre); ?>" />
                                            <datalist id="CClist">
                                                <?php
                                                $queryCC = "SELECT DISTINCT costcentre FROM staff";
                                                $resultCC = $conn->query($queryCC);
                                                while ($recordCC = $resultCC->fetch_assoc()) {
                                                    echo "<option value='" . htmlspecialchars($recordCC['costcentre']) . "'>";
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="small mb-1" for="telephone_office">Telephone (office)</label>
                                            <input class="form-control py-1 input-user" id="telephone_office" name="telephone_office" placeholder="Optional to Fill"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-5 form-group">
                                            <label class="small mb-1" for="department">Division/Department/Unit</label>
                                            <input class="form-control py-1" id="department" name="department" value="<?php echo htmlspecialchars($staff_dept); ?>" disabled />
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="small mb-1" for="email">Email</label>
                                            <input class="form-control py-1" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="small mb-1" for="access_role">Access <span style="color:red;">*</span></label>
                                            <div class="form-box">
                                                <div class="input-wrapper">
                                                    <span class="authorization-label">MAINT - YSD Maintenance Staff</span>
                                                    <input type="radio" id="maint" name="access_role" value="MAINT" required>
                                                </div>
                                                <div class="input-wrapper">
                                                    <span class="authorization-label">OLM - OLM Maintenance</span>
                                                    <input type="radio" id="olm" name="access_role" value="OLM">
                                                </div>
                                                <div class="input-wrapper">
                                                    <span class="authorization-label">PLAN - YSD Planner</span>
                                                    <input type="radio" id="plan" name="access_role" value="PLAN">
                                                </div>
                                                <div class="input-wrapper">
                                                    <span class="authorization-label">SYSAD - System Admin</span>
                                                    <input type="radio" id="sysad" name="access_role" value="SYSAD">
                                                </div>
                                                <div class="input-wrapper">
                                                    <span class="authorization-label">WEB - Webwork User</span>
                                                    <input type="radio" id="web" name="access_role" value="WEB">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="approver">Approval by (*)</label>
                                        <select class="form-control select input-user" name="approver" id="approver" required>
                                            <option selected="selected" value="">Click and type to search</option>
                                            <?php
                                            // Only retrieve staff who have role as HOD
                                            $queryAppr = "SELECT staff_id, staff_name FROM staff WHERE role = 'HOD'";
                                            $stmtAppr = $conn->query($queryAppr);
                                            while ($recordAppr = $stmtAppr->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($recordAppr['staff_id']) . "'>" . htmlspecialchars($recordAppr['staff_name']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="terms">
                                            <input type="checkbox" id="terms" name="terms" required>
                                            I agree to the terms and conditions
                                            <ul class="terms-text">
                                                <li>User is responsible to ensure that there is no abuse of ID usage.</li>
                                                <li>Sharing of user ID is not recommended. Anything that happens will be under the user's responsibility.</li>
                                            </ul>
                                        </label>
                                    </div>
                                    <div class="applicant-section">
                                        <button type="submit" class="submit-button">Submit</button>
                                        <button type="button" class="submit-button" onclick="window.location.href='homepage.php';" style="margin-left: 10px; background-color: #6c757d;">Back</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<style>
    body, h1, h2, h4, h5, h6 {
        font-family: Arial, Helvetica, sans-serif;
        background: black;
        font-size: smaller;
    }

    .h3 {
        font-size: larger;
    }

    .card-header {
        background-color: midnightblue; 
        color: whitesmoke; 
        padding: 20px;
        text-align: center;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    }
    
    .container {
        max-width: 700px;
        margin: 0 auto;
        background: gainsboro;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; /* Adds space between columns */
}

.col-md-3 {
    flex: 1 1 23%; /* Adjusts width to fit 4 columns in a row with some spacing */
}

input {
    width: 100%; /* Makes input fields full width within their container */
    padding: 0.375rem 0.75rem; /* Adjust padding to match Bootstrap form control */
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.full-name-group {
    margin-bottom: 20px; /* Adds space below the full name field */
}

    .form-group {
        margin-bottom: 15px;
        color: black;
        padding-top: 2%;
    }

    .form-group label, .authorization-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: black;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: lightsteelblue; /* Set the background color of the input fields */
        color: black; /* Set the text color inside the input fields */
    }

    .form-group input[type="radio"], .form-group input[type="checkbox"] {
        width: auto;
        margin-right: 10px;
        color: black;
    }

    .form-group button {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .form-group button:hover {
        background-color: white;
    }

    .form-box {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .form-row label:first-child {
        font-weight: bold; /* Example styling - you can customize this as needed */
        color: black; /* Example color - you can customize this as needed */
    }
    
    .form-row {
    display: flex;
    flex-wrap: wrap;
}

.col-md-3 {
    flex: 1;
    max-width: 25%;
    padding: 0 15px;
}

.col-md-5 {
    flex: 1;
    max-width: 41.66667%;
    padding: 0 15px;
}

.col-md-6 {
    flex: 1;
    max-width: 50%;
    padding: 0 15px;
}

    .my-4 {
        margin-top: 4rem;
        margin-bottom: 4rem;
    }

    .input-wrapper {
        display: flex;
        align-items: center;
        padding: 5px 0; 
    }

    .input-wrapper span {
        margin-right: 10px;
        flex: 1; /* Allow the span to take up remaining space */
    }

    .input-user {
    background-color: white !important; /* light yellow (cornsilk) to differentiate */
    }

    .applicant-section {
        margin-top: 20px;
        display: flex;
        justify-content: center; 
    }
    .authorization-checkbox {
        margin-right: 50px;
    }

    .submit-button {
        background-color: midnightblue;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .submit-button:hover {
        background: #008080;;
    }
</style>
