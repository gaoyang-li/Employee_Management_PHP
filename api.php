<?php

session_start();
function allAction()
{
    $action = $_POST['action'];
    if ($action != 'login') {
        $loginUser = $_SESSION['user'];
        if (!$loginUser) {
            returnData('100', "Please login first");
        }
    }
    $action();

}
allAction();

function mysql()
{
    $servername = "localhost:3307";
    $username = "root";
    $password = "123456";
    $dbname = "test";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        returnData('001', "mysql connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function login()
{
    $emp_id = $_POST['emp_id'];
    $sqlFind = "select employee.* from employee left join department  on  department.dept_number = employee.dept_number  where manager_emp_id='$emp_id' ";

    $con = mysql();
    $resultone = mysqli_query($con, $sqlFind);
    $_SESSION['user'] = $emp_id;
    returnData(200, " Login succeeded");
}

function add()
{
    $name = htmlentities($_POST['name']);
    $emp_id = $_POST['emp_id'];
    $address = $_POST['address'];
    $salary = trim($_POST['salary']);
    $dob = $_POST['dob'];
    $nin = $_POST['nin'];
    $department = $_POST['department'];
    $emergency_name = $_POST['emergency_name'];
    $emergency_relationship = $_POST['emergency_relationship'];
    $emergency_phone = $_POST['emergency_phone'];
    if (!$name || !$emp_id || !$address || !$salary || !$dob || !$nin || !$department || !$emergency_name || !$emergency_relationship || !$emergency_phone) {
        returnData('001', 'Parameter exception');
    }
    $salary = str_replace(',', '', $salary);
    $patter_number = "/^[0-9]*$/";
    $patter_date = "/^[0-3][0-9][\/][0-1][0-9][\/][0-9]{4}$/";
    $patter_phone = "/^07\d{3}[ ]\d{3}[ ]\d{3}$/";
    $patter_nin = "/^[a-z]{2}\d{6}[a-z]{1}$/";
    $patter_empId = "/^\d{2}[-]\d{7}$/";
    $patter_salary = "/^[0-9]+(.[0-9]{1,2})?$/";
    if (preg_match($patter_number, $name)) {
        returnData('001', 'Name cannot have numbers');
    }
    if (!preg_match($patter_empId, $emp_id)) {
        returnData('001', 'Invalid Emp_id format');
    }
    if (!preg_match($patter_salary, $salary)) {
        returnData('001', 'salary must be an integer or two decimal places after the decimal point');
    }
    $salary = number_format($salary, 2);
    if (!preg_match($patter_date, $dob)) {
        returnData('001', 'Invalid date-of-birth format');

    }
    if (!preg_match($patter_nin, $nin)) {
        returnData('001', 'Invalid nin format');

    }
    if (preg_match($patter_number, $emergency_name)) {
        returnData('001', 'Emergency Name cannot have numbers');

    }

    if (!preg_match($patter_phone, $emergency_phone)) {
        returnData('001', 'invalid Emergency Phone format,eg:07111 222 333');

    }
    $con = mysql();
    $sqlFind = "select * from employee where emp_id='$emp_id' ";

    $resultone = mysqli_query($con, $sqlFind);
    if ($resultone->num_rows >= 1) {
        returnData(002, "emp_id： $emp_id already exists");
    }
    $admin_emp_id = $_SESSION['user'];
    $sql = " INSERT INTO  `employee`  (`emp_id`, `name`, `address`, `salary`, `date_of_birth`, `nin`, `dept_number`, `emergency_name`, `emergency_relationship`, `emergency_phone`, `admin_emp_id`) VALUES ('$emp_id', \"$name\", '$address', '$salary', '$dob', '$nin', '$department', '$emergency_name', '$emergency_relationship', '$emergency_phone','$admin_emp_id');
";

    $result = mysqli_query($con, $sql);
    if ($result && mysqli_affected_rows($con) > 0) {
        $res = 'Successfully added!';

    } else {
        $res = 'Add failed';

    }
    returnData(200, $res);
}

function lists()
{
    $emp_id = $_POST['emp_id'];
    $department = $_POST['department'];
    $emergency_relationship = $_POST['emergency_relationship'];
    $where = 'where 1=1  ';
    if ($emp_id) {
        $where .= "and emp_id ='$emp_id'";
    }
    if ($department) {
        $where .= "and employee.dept_number ='$department'";
    }
    if ($emergency_relationship) {
        $where .= "and emergency_relationship ='$emergency_relationship'";
    }
    $sqlFind = "select `emp_id`, employee.name, `address`, `salary`, `date_of_birth`, `nin`, department.name, `emergency_name`, `emergency_relationship`, `emergency_phone`,manager_emp_id from employee left  join department on employee.dept_number = department.dept_number " . $where;
    $con = mysql();
    $result = mysqli_query($con, $sqlFind);
    $row = mysqli_fetch_all($result);
    foreach ($row as $rk => $rv) {

        $maid = $rv['10'];
        $sqlM = "select * from employee where emp_id ='$maid'";
        $result = mysqli_query($con, $sqlM);
        $row1 = mysqli_fetch_array($result);
        $row[$rk]['manager_name'] = $row1['name'] ?? "";
    }
    returnData(200, '', $row);
}

function info()
{
    $emp_id = $_POST['emp_id'];
    if (!$emp_id) {
        returnData('001', 'Parameter exception');
    }
    $sql = "select * from employee  where  emp_id ='$emp_id'";
    $con = mysql();
    $result = mysqli_query($con, $sql);
    if ($result->num_rows == 0) {
        returnData(002, "emp_id： $emp_id does not exist");
    }
    $row = mysqli_fetch_array($result);
    returnData(200, '', $row);
}

function update()
{
    $name = htmlentities($_POST['name']);
    $emp_id = $_POST['emp_id'];
    $address = $_POST['address'];
    $salary = trim($_POST['salary']);
    $dob = $_POST['dob'];
    $nin = $_POST['nin'];
    $department = $_POST['department'];
    $emergency_name = $_POST['emergency_name'];
    $emergency_relationship = $_POST['emergency_relationship'];
    $emergency_phone = $_POST['emergency_phone'];
    if (!$name || !$emp_id || !$address || !$salary || !$dob || !$nin || !$department || !$emergency_name || !$emergency_relationship || !$emergency_phone) {
        returnData('001', 'Parameter exception');
    }
    $salary = str_replace(',', '', $salary);
    $patter_number = "/^[0-9]*$/";
    $patter_date = "/^[0-3][0-9][\/][0-1][0-9][\/][0-9]{4}$/";
    $patter_phone = "/^07\d{3}[ ]\d{3}[ ]\d{3}$/";
    $patter_nin = "/^[a-z]{2}\d{6}[a-z]{1}$/";
    $patter_salary = "/^[0-9]+(.[0-9]{1,2})?$/";

    if (preg_match($patter_number, $name)) {
        returnData('001', 'Name cannot have numbers');
    }
    if (!preg_match($patter_salary, $salary)) {
        returnData('001', 'salary must be an integer or a two-decimal fraction');
    }
    $salary = number_format($salary, 2);
    if (!preg_match($patter_date, $dob)) {
        returnData('001', 'Invalid date-of-birth format. shoule be dd-mm--yy');
    }
    if (!preg_match($patter_nin, $nin)) {
        returnData('001', 'Invalid nin format');
    }
    if (preg_match($patter_number, $emergency_name)) {
        returnData('001', 'Emergency Name cannot have numbers');
    }

    if (!preg_match($patter_phone, $emergency_phone)) {
        returnData('001', 'invalid Emergency Phone format, should be 07xxx xxx xxx');
    }
    $con = mysql();
    $sqlFind = "select * from employee where emp_id='$emp_id' ";

    $resultone = mysqli_query($con, $sqlFind);
    if ($resultone->num_rows == 0) {
        returnData(002, "emp_id： $emp_id does not exist");
    }
    $admin_emp_id = $_SESSION['user'];
    $sql = "UPDATE `employee`  SET   `name`=\"$name\", `address`='$address', `salary`='$salary ', `date_of_birth`='$dob', `nin`='$nin', `dept_number`='$department', `emergency_name`='$emergency_name', `emergency_relationship`='$emergency_relationship', `emergency_phone`='$emergency_phone' ,`admin_emp_id`='$admin_emp_id'WHERE (`emp_id`='$emp_id')
";

    $result = mysqli_query($con, $sql);
    if ($result && mysqli_affected_rows($con) > 0) {
        returnData(200, 'Successfully Update!');
    } else {
        returnData(003, 'Update failed');
    }
}

function delete()
{
    $emp_id = $_POST['emp_id'];
    if (!$emp_id) {
        returnData('001', 'Parameter exception');
    }
    $con = mysql();
    $admin_emp_id = $_SESSION['user'];
    $sql1 = "UPDATE `employee`  SET   `admin_emp_id`='$admin_emp_id'WHERE (`emp_id`='$emp_id')";
    $result1 = mysqli_query($con, $sql1);
    $sql = "delete from employee  where  emp_id ='$emp_id'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        returnData(200, 'Delete succeeded');
    }
    returnData(001, 'Delete failed');
}

function birthday()
{
    $month = date("m");
    $sql = " call birth('%/$month/%')";
    $con = mysql();
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_all($result);
    returnData(200, '', $row);
}

function department()
{
    $sql = "select * from department ";
    $con = mysql();
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_all($result);
    returnData(200, '', $row);
}

function returnData($code, $errorMsg = '', $data = '')
{
    exit(json_encode(['code' => $code, 'data' => $data, 'errorMsg' => $errorMsg]));
}