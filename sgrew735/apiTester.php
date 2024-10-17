<?php
session_start();
require_once('./data/config.inc.php');
require_once('./data/DatabaseHelper.php');

include('./includes/header.php');

?>

<div class="container">
    <h2>API List</h2>
    <table>
        <thead>
            <tr>
                <th>URL</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="api/circuits.php">/api/circuits.php</a></td>
                <td>Returns all the circuits</td>
            </tr>
            <tr>
                <td><a href="api/circuits.php?ref=monaco">/api/circuits.php?ref=monaco</a></td>
                <td>Returns just a specific circuit (Monaco)</td>
            </tr>
            <tr>
                <td><a href="api/constructors.php">/api/constructors.php</a></td>
                <td>Returns all the constructors</td>
            </tr>
            <tr>
                <td><a href="api/constructors.php?ref=mclaren">/api/constructors.php?ref=mclaren</a></td>
                <td>Returns just a specific constructor (McLaren)</td>
            </tr>
        </tbody>
    </table>
</div>

<?php
include('./includes/footer.php');
?>