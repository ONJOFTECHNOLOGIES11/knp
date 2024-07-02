<!---edit modal -->
<div class="modal fade" id="e<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="editCandidateLabel<?php echo $id; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCandidateLabel<?php echo $id; ?>">Edit Candidate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php 
                        // Fetch current candidate data
                        require 'dbcon.php';
                        $candidate_query = $conn->query("SELECT * FROM candidate WHERE candidate_id = '$id'");
                        $candidate_data = $candidate_query->fetch_array();
                    ?>
                    <input type="hidden" name="candidate_id" value="<?php echo $candidate_data['candidate_id']; ?>">

                    <div class="form-group">
                        <label>Position</label>
                        <select class="form-control" name="position" id="edit_position_<?php echo $id; ?>" required>
                            <option <?php if ($candidate_data['position'] == 'President') echo 'selected'; ?> value="President">President</option>
                            <option <?php if ($candidate_data['position'] == 'CS Finance') echo 'selected'; ?> value="CS Finance">CS Finance</option>
                            <option <?php if ($candidate_data['position'] == 'Secretary General') echo 'selected'; ?> value="Secretary General">Secretary General</option>
                            <option <?php if ($candidate_data['position'] == 'Woman Representative') echo 'selected'; ?> value="Woman Representative">Woman Representative</option>
                            <option <?php if ($candidate_data['position'] == 'Governor') echo 'selected'; ?> value="Governor">Governor</option>
                        </select>
                    </div>

                    <div class="form-group" id="edit_department_group_<?php echo $id; ?>" style="display: <?php echo ($candidate_data['position'] == 'Governor') ? 'block' : 'none'; ?>;">
                        <label>Department</label>
                        <input class="form-control" type="text" name="department" value="<?php echo $candidate_data['department']; ?>" placeholder="Enter department name">
                    </div>

                    <div class="form-group">
                        <label>Party</label>
                        <input class="form-control" type="text" name="party" value="<?php echo $candidate_data['party']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Firstname</label>
                        <input class="form-control" type="text" name="firstname" value="<?php echo $candidate_data['firstname']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Lastname</label>
                        <input class="form-control" type="text" name="lastname" value="<?php echo $candidate_data['lastname']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Year Level</label>
                        <select class="form-control" name="year_level" required>
                            <option <?php if ($candidate_data['year_level'] == '1st Year') echo 'selected'; ?>>1st Year</option>
                            <option <?php if ($candidate_data['year_level'] == '2nd Year') echo 'selected'; ?>>2nd Year</option>
                            <option <?php if ($candidate_data['year_level'] == '3rd Year') echo 'selected'; ?>>3rd Year</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" name="gender" required>
                            <option <?php if ($candidate_data['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option <?php if ($candidate_data['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image">
                        <p class="help-block">Current Image: <img src="<?php echo $candidate_data['img']; ?>" width="50" height="50" class="img-rounded"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of modal -->

<script>
document.getElementById('edit_position_<?php echo $id; ?>').addEventListener('change', function() {
    var departmentGroup = document.getElementById('edit_department_group_<?php echo $id; ?>');
    if (this.value === 'Governor') {
        departmentGroup.style.display = 'block';
    } else {
        departmentGroup.style.display = 'none';
    }
});
</script>

<?php
if (isset($_POST['update'])) {
    $candidate_id = $_POST['candidate_id'];
    $position = $_POST['position'];
    $party = $_POST['party'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $year_level = $_POST['year_level'];
    $gender = $_POST['gender'];
    $department = isset($_POST['department']) ? $_POST['department'] : '';

    if (!empty($_FILES['image']['name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_name = addslashes($_FILES['image']['name']);
        $location = "upload/" . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], $location);
    } else {
        $location = $candidate_data['img'];
    }

    $conn->query("UPDATE candidate SET position='$position', party='$party', firstname='$firstname', lastname='$lastname', year_level='$year_level', gender='$gender', department='$department', img='$location' WHERE candidate_id='$candidate_id'") or die($conn->error);
    header('location:candidate.php');
}
?>
