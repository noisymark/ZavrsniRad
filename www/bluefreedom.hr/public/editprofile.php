<?php

session_start();
if(!$_SESSION['auth']){header('location: authorisation.php');}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "components/header.php"?>
<?php include "components/styles.php"?>
<link rel="stylesheet" href="css/editprofile.css">
<body>

<?php include "navbar.php"?>

<div class="container rounded mt-5 mb-5">
    <div class="row"> 
        <div class="col-md-2"></div>
        <div class="col-md-3 rounded-start"> 
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img id="userphoto" class="mt-5" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__340.png">
                <span style="padding-top:30px;" class="font-weight-bold">User</span>
                <span style="text-decoration:underline; font-weight: bolder;" class="text-50">UserEmail@address.com</span>
                <span>
                </span>
            </div>
        </div>
        <div class="col-md-5 rounded-end">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-center">Profile Settings</h4>
                </div> <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="labels">First Name</label>
                        <input type="text" class="form-control" placeholder="First name:" value="">
                    </div>
                    <div class="col-md-6">
                        <label class="labels">Last name</label>
                        <input type="text" class="form-control" value="" placeholder="Last name:">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels">Phone Number</label>
                        <input type="text" class="form-control" placeholder="Phone Number:" value="">
                    </div>
                    <div class="col-md-12">
                        <label class="labels">Address</label>
                        <input type="text" class="form-control" placeholder="Address:" value="">
                    </div>
                    <div class="col-md-12">
                        <label class="labels">Email</label>
                        <input type="text" class="form-control" placeholder="Email address:" value="">
                    </div>
                    <div class="col-md-12">
                        <label class="labels">Workplace</label>
                        <input type="text" class="form-control" placeholder="Workplace:" value="">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6"><label class="labels">Country</label>
                    <input type="text" class="form-control" placeholder="Country:" value="">
                </div>
                <div class="col-md-6"><label class="labels">State/Region/Province</label>
                <input type="text" class="form-control" value="" placeholder="State:">
            </div>
        </div>
        <div class="mt-5 text-center"><button class="btn btn-secondary profile-button" type="button">Save Profile</button>
    </div>
    
</div>

</div>
<div class="col-md-2"></div>
</div>
</div>
</div>
</div>

<?php include "components/footer.php"?>
<?php include "components/scripts.php"?>
</body>
</html>