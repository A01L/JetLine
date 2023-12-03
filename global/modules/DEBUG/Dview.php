<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error notice | DEBUG</title>
</head>
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

body {
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #3067D4;
    color: #ffffff;
}

.main {
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.title__err {
    font-weight: 900;
    font-size: 70px;
}

.subtitle__err {
    font-size: 20px;
    max-width: 90%;
    text-align: center;
    padding: 7px;
}

.return {
    text-decoration: none;
    color: #ffffff;
    margin-top: 40px;
    padding: 20px;
    background-color: #78a5fd;
    border-radius: 30px;

    transition: .1s ease;
}

.return:hover {
    background-color: #0aa1f1;
}
.orange{
    background-color: orange;
    padding: 5px;
    border-radius: 5px;
}
.red{
    background-color: red;
    margin-left: 5px;
    padding: 5px;
    border-radius: 5px;
}
.purple{
    background-color: purple;
    margin-left: 5px;
    padding: 5px;
    border-radius: 5px;
}
.syst{
    border: 3px dotted white;
    margin-left: 5px;
    padding: 2px;
    border-radius: 5px;
}
.msg{
    background-color: white;
    padding: 3px;
    border-radius: 5px;
    color: #3067D4;
}
</style>

<?php
if($errno == 1){
    $type = 'FATAL';
    $color = 'red';
}
elseif($errno == 8){
    $type = 'NOTICE';
    $color = 'orange';
}
else{
    $type = 'UNKOWN';
    $color = 'purple';
}
?>

<body>
    <div class="main">
        <div class="title__err">DEBUG</div>
        <div class="subtitle__err"><?php echo "<span class='".$color."'>".$type."</span> <span class='syst'>Conflict in :</span> <span class='orange'>".$errfile."</span><span class='red'>Line: ".$errline."</span>"; ?></div>
        <div class="subtitle__err"><?php echo "<span class='msg'>".$errstr."</span>"; ?></div>

        <a href="<?php echo Router::url(); ?>" class="return">RELOAD PAGE</a>
    </div>
</body>
</html>