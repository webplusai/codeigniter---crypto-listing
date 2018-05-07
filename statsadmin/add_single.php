<?php
include  'database.php';
  $sql2='Select * from tbl_project_categories';
  $result2 = $conn->query($sql2);
?>
<div class="row">
    <form class="col s12" method="post" action="add_single_processing.php" id="add_data_form">
        <div class="row">
            <div class="input-field col s6">
                <input name="ICOName" id="first_name" type="text" class="validate"">
                <label for="first_name">Ico Name</label>
            </div>
            <div class="input-field col s6">
                <input placeholder="0.0" name="TotalUSD" id="password" type="text" class="validate">
                <label for="password">TotalUSD</label>
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <label for="StartDate">StartDate</label>
                <input name="StartDate" id="StartDate" type="date" class="datepicker">
            </div>
            <div class="col s6">
                <label for="disabled">EndDate</label>
                <input name="EndDate" id="disabled" type="date" class="validate" class="datepicker">
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6">
                <div class="input-field col s12">
                    <select class='categorey_select'>
                        <option value="" disabled selected>Choose Category</option>
                        <?php
                        while($row1 = $result2->fetch_assoc())
                        {?>
                        <option  value="<?php echo $row1['ProjCatID'];?>"><?php echo $row1['ProjCatName'];?></option>
                        <?php }?>
                        <input type="hidden" id="categorey_input" name='cat' value="<?php echo $row1['ProjCatID'];?>">
                    </select>
                </div>
            </div>
            <div class="input-field col s6" style="margin-top: 30px">
                <input name="Link" id="email" type="text" class="validate">
                <label for="email">Link</label>
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <label for="email">Comments</label>
                <textarea style="height: 7rem !important;text-align: left !important;" name="Comments" id="email" type="text" class="validate"></textarea>
            </div>
            <div class="input-field col s6" style="margin-top:70px">
                <input name="Investors" id="email" type="text" class="validate"  value="<?php echo $row['Investors']?>">
                <label for="email">Investors</label>
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <label for="Percent">Percent</label>
                <input name="Percent" id="Percent" type="text">
            </div>
        </div>
        <div class="row">
            <div class="col s12 from_input">
                <button style="width: 39%;margin-left: 29%" type="submit" class="waves-effect waves-light btn-large form_input" id="add_single_form_button">Submit</button>
            </div>
        </div>
    </form>
</div>