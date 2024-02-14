<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>displays all employees whose birthday is in the current calendar month</title>
    <script src="../js/jquery-3.6.1.min.js" type="text/javascript"></script>
</head>

<body>
    <h1>displays all employees whose birthday is in the current calendar month</h1>
    <h3 style="color: red">Current month:
        <?php echo date('m') ?>
    </h3>
    <table border="1" id="table" cellspacing=0></table>
</body>

</html>
<script type="text/javascript">
    function getList() {
        $.post("../api.php", {
            'action': 'birthday'
        }, function (data) {
            if (data.code == 200) {
                var leng = data.data.length;
                var returnData = data.data;
                var tr = "<tr>\n" +
                    "        <th>emp_id</th>\n" +
                    "        <th>name</th>\n" +
                    "        <th>address</th>\n" +
                    "        <th>salary</th>\n" +
                    "        <th>dob</th>\n" +
                    "        <th>nin</th>\n" +
                    // "        <th>department</th>\n" +
                    "        <th>emergency_name</th>\n" +
                    "        <th>emergency_relationship</th>\n" +
                    "        <th>emergency_phone</th>\n" +

                    "    </tr>";
                for (var i = 0; i < leng; i++) {
                    tr += " <tr>" +
                        "            <td>" + returnData[i][0] + "</td>\n" +
                        "            <td>" + returnData[i][1] + "</td>" +
                        "            <td>" + returnData[i][2] + "</td>" +
                        "            <td>" + returnData[i][3] + "</td>" +
                        "            <td>" + returnData[i][4] + "</td>" +
                        "            <td>" + returnData[i][5] + "</td>" +
                        // "            <td>"+returnData[i][6]+"</td>" +
                        "            <td>" + returnData[i][7] + "</td>" +
                        "            <td>" + returnData[i][8] + "</td>" +
                        "            <td>" + returnData[i][9] + "</td>" +

                        "        </tr>"
                }
                $("#table").html(tr)
            }
        }, 'json');
    }
    $(function () {
        getList();
    })
</script>

<style>
    html,
    body {
        padding: 15px 20px;
        font-family: 'Open Sans', Arial, Helvetica, sans-serif;
        font-size: 15px;
        color: #6d6d6d;
    }
</style>