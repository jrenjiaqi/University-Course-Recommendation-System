<?php
include 'private/database.inc.php';

session_start();
if (isset($_SESSION['email']) && isset($_SESSION['name'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php
        include "head.inc.php";
        ?>
        <title>ICT 2103 University</title>
    </head>
    <body>
    <?php
    include "nav.inc.php";
    ?>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>University CCA <b>Details</b></h2></div>
                    <form class="d-flex" action="admin_cca_search.php" method="post">
                        <input class="form-control mr-sm-2" type="text" name="ccasearch" value="<?php if(isset($_POST['ccasearch'])){echo $_POST['ccasearch'];} ?>" id="ccasearch" class="form-control"
                               size="23" placeholder="Search CCA..." required>
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </form>
                    <div class="col-sm-12"></div><br>
                    <div class="col-sm-5"></div>
                    <span class="filter-icon"><i class="fa fa-filter"></i></span>
                    <label>Uni Type</label>
                    <div class="col-md-2">
                        <select class="form-control" name="unidropdown" id="unidropdown">
                            <option value="all">All University</option>
                            <?php
                            $uni_list_rows = $uni_list_collection->find();
                            foreach($uni_list_rows as $row){
                            echo "<option value=" . $row['uni_name'] . ">" . $row['uni_name'] . "</option>";
                        }
                            
                            
                            ?>
                        </select>
                    </div>
                    <span class="filter-icon"><i class="fa fa-filter"></i></span>
                    <label>CCA Type</label>
                    <div class="col-md-2">
                        <select class="form-control" name="catdropdown" id="catdropdown">
                            <option value="all">All Category</option>
                            <?php
                            $uni_cca_category_rows = $uni_cca_category_collection->find();
                            foreach($uni_cca_category_rows as $row){
                            echo "<option value=" . $row['category_name'] . ">" . $row['category_name'] . "</option>";
                        }
                            ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-info add-new" data-target='#addcca' data-toggle='modal'><i
                                class="fa fa-plus"></i> Add New
                    </button>
                </div>
            </div>
            <div id="container">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th hidden>CCA ID</th>
                        <th>University</th>
                        <th>CCA Category</th>
                        <th>CCA</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                <?php
                //Fetch cca Data from database
                $result_JSON = $uni_cca_collection->find($filter, $options)->toArray();
                $cca_category_JSON = $uni_cca_category_collection->find()->toArray();
                $uni_JSON = $uni_list_collection->find()->toArray();
                foreach($result_JSON as $cca_OBJ){
                    foreach($cca_category_JSON as $cca_cat_OBJ){
                        if($cca_OBJ['category_id'] == $cca_cat_OBJ['category_id']){
                            $cca_OBJ['category_name'] = $cca_cat_OBJ['category_name'];
                            continue;
                        }
                    }
                }
                
                foreach($result_JSON as $cca_OBJ){
                    foreach($uni_JSON as $uni_OBJ){
                        if($cca_OBJ['uid']==$uni_OBJ['uid']){
                            $cca_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                            continue;
                        }
                    }
                }
                foreach($result_JSON as $obj){
                    echo '<td hidden>' .$obj['cca_id']. '</td>';
                    echo '<td>' .$obj['uni_name']. '</td>';
                    echo '<td>' .$obj['category_name']. '</td>';
                    echo '<td>' .$obj['cca_name']. '</td>';
                ?>
                        <td>
                            <a class="edit" title="Edit" data-toggle='modal'><i
                                        class="material-icons">&#xE254;</i></a>
                            <a href="admin_cca_delete.php?id=<?php echo $obj['cca_id']; ?>" class="delete"
                               title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Add Modal HTML -->
<div id="addcca" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="admin_add.php" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Add CCA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="course">University: </label>
                        <select class="form-control" id="uni" name="uni" required>
                            <option value="NUS">NUS</option>
                            <option value="NTU">NTU</option>
                            <option value="SMU">SMU</option>
                        </select>
                        <br>
                        <label for="course">CCA Category: </label>
                        <select class="form-control" id="catname" name="catname" required>
                            <option value="Arts & Culture">Arts & Culture</option>
                            <option value="Sports">Sports</option>
                            <option value="Religion">Religion</option>
                            <option value="Interest Clubs">Interest Clubs</option>
                        </select>
                        <br><label for="cname">CCA: </label><br>
                        <input type="text" id="cname" name="cname" size="50" required><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-info" name="addcca" id="addcca">Add CCA</button>
                </div>
            </form>
        </div>
    </div>
</div>
  
<!-- Edit Modal HTML -->
<div id="updatecca" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="admin_update.php" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Edit University CCA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="row" id="row">
                    <div class="form-group">
                        <label for="course">University: </label>
                        <select class="form-control" id="euni" name="euni" required>
                            <option value="1">NUS</option>
                            <option value="2">NTU</option>
                            <option value="3">SMU</option>
                        </select>
                        <br>
                        <label for="course">CCA Category: </label>
                        <select class="form-control" id="ecat" name="ecat" required>
                            <option value="1">Arts & Culture</option>
                            <option value="2">Sports</option>
                            <option value="3">Religion</option>
                            <option value="4">Interest Clubs</option>
                        </select>
                        <br><label for="ecca">CCA: </label><br>
                        <input type="text" id="ecca" name="ecca" size="50" required><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="cca_id" name="cca_id" value="" type="hidden">
                    <input id="cca_name" name="cca_name" value="" type="hidden">
                    <input name="updateCCA" value= "0" type="hidden">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-info" name="updatecca" id="updatecca">Edit CCA</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
     $(document).ready(function () {
         $("#unidropdown, #catdropdown").on('change', function () {
             var unidropdown = $("#unidropdown").val();
             var catdropdown = $("#catdropdown").val();

             $.ajax({
                 url: "admin_fetch.php",
                 type: "POST",
                 data: {
                     unidropdown: unidropdown,
                     catdropdown: catdropdown
                 },
                 success: function (data) {
                     $("#container").html(data);
                 }
             })
         });
     });

     $(document).ready(function () {
         $(document).on('click', '.edit', function () {
             $('#updatecca').modal('show');
             $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function () {
                 return $(this).text();
             }).get();
             console.log(data);

            $cca_id = data[0]; // colum 1
            $cca_name = data[1]; //column2
            // Update placeholder values in editing form
            document.getElementById("cca_id").setAttribute('value',$cca_id);
            document.getElementById("cca_name").setAttribute('value',$cca_name);
         });
     });
</script>

</body>
</html>
    <?php
} else{
    header("Location: login.php");
    exit();
}
?>