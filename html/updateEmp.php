<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Employee</title>
    <script src="../js/jquery-3.6.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
</head>

<body>

    <h1>Update Employee</h1>
    <form id="form">
        <h3>emp_id:
            <?php echo $_GET['emp_id'] ?>
        </h3>
        <input class="emp_id" type="hidden" value="<?php echo $_GET['emp_id'] ?>">
        <div class="item">
            <span>name:</span>
            <input type="text" class="name">
        </div>
        <div class="item">
            <span>address:</span>
            <input type="text" class="address">
        </div>
        <div class="item">
            <span>salary:</span>
            <input type="text" class="salary">
        </div>

        <div class="item">
            <span>dob:</span>
            <input type="text" class="dob" placeholder="01/01/1960">
        </div>
        <div class="item">
            <span>nin:</span>
            <input type="text" class="nin">
        </div>
        <div class="item">
            <span>department:</span>
            <select class="department">
                <option value=""></option>
            </select>
        </div>
        <div class="item">
            <span>emergency_name:</span>
            <input type="text" class="emergency_name">
        </div>
        <div class="item">
            <span>emergency_relationship:</span>
            <select class="emergency_relationship">
                <option></option>
                <option>Father</option>
                <option>Mother</option>
                <option>Husband</option>
                <option>Wife</option>
                <option>Girlfriend</option>
                <option>Boyfriend</option>
                <option>Civil Partner</option>
            </select>
        </div>
        <div class="item">
            <span>emergency_phone:</span>
            <input type="text" class="emergency_phone">
        </div>

        <button>submit</button>
    </form>
</body>

</html>
<script type="text/javascript">
    $.post("../api.php", {
        'action': 'department'
    }, function (data) {
        if (data.code == 200) {
            var leng = data.data.length;
            var returnData = data.data;

            var op;
            for (var i = 0; i < leng; i++) {
                op += "<option value='" + returnData[i][0] + "'>" + returnData[i][1] + "</option>";
            }
            $(".department").html(op)
        }

    }, 'json');


    $(function () {

        $.post("../api.php", {
            'emp_id': $(".emp_id").val(),
            'action': 'info'
        }, function (data) {
            if (data.code == 200) {
                $(".name").val(data.data.name);
                $(".address").val(data.data.address);
                $(".salary").val(data.data.salary);
                $(".dob").val(data.data.date_of_birth);
                $(".nin").val(data.data.nin);
                $(".department").val(data.data.dept_number);
                $(".emergency_name").val(data.data.emergency_name);
                $(".emergency_relationship").val(data.data.emergency_relationship);
                $(".emergency_phone").val(data.data.emergency_phone);
            } else {
                alert(data.errorMsg);
            }

        }, 'json');
        // form data
        $("#form").submit(function () {
            $.post("../api.php", {
                'emp_id': $(".emp_id").val(),
                'name': $(".name").val(),
                'address': $(".address").val(),
                'salary': $(".salary").val(),
                'dob': $(".dob").val(),
                'nin': $(".nin").val(),
                'department': $(".department").val(),
                'emergency_relationship': $(".emergency_relationship").val(),
                'emergency_name': $(".emergency_name").val(),
                'emergency_phone': $(".emergency_phone").val(),
                'action': 'update'
            }, function (data) {
                alert(data.errorMsg);
                if (data.code == 200) {
                    window.location.href = "list.html";
                }
                if (data.code == 100) {
                    window.location.href = "login.html";
                }

            }, 'json');




            return false;
        });
    })
</script>