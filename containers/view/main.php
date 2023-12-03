<?php
if(isset($_POST['st'])){
    echo 'posted!';
    DTC::storage('test', 'testf');
}
echo asdassd();
?>
<html>
    <body>
        <form action="#" method='post' enctype="multipart/form-data">
            <input type="text" name="st" value="ok" hidden>
            <input type="file" name="testf">
            <button>tab</button>
        </form>
    </body>
</html>