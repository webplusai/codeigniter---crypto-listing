  <?php 
include  'database.php';
 $id=$_GET['id'];
  $sql = 'SELECT ID,ICOName,StartDate,EndDate,TotalUSD,ProjCatName,Category,Link,Symbol,Year,BTC,Waves,NEM,EMC,ETH,ETC,LTC,LISK,BCY,USD,USDT,EUR,RMB,Comments,Investors,PercentRaised FROM tbl_icohistory as tih
          left join tbl_project_categories as tpc on tpc.ProjCatID=tih.CSProjID
          where tih.ID='.$id;
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $sql2='Select * from tbl_project_categories';
  $result2 = $conn->query($sql2);
?>
<div id="modal1" class="modal">
  <div class="modal-content">
<div class="row">
  <form class="col s12" method="post" action="update.php" id="edit_form">
    <input name="ID" id="email" type="hidden" class="validate"  value="<?php echo $row['ID']?>">
    <div class="row">
      <div class="col s6">
        <label for="first_name">Ico Name</label>
        <input name="ICOName" id="first_name" type="text" class="validate" value="<?php echo $row['ICOName']?>">
      </div>
      <div class="col s6">
        <label for="password">TotalUSD</label>
        <input name="TotalUSD" id="password" type="text" class="validate" value="<?php echo $row['TotalUSD']?>">
      </div>
    </div>
    <div class="row">
      <div class="col s6">
        <label for="last_name">StartDate</label>
        <input name="StartDate" id="StartDate" type="date" class="datepicker" value="<?php echo $row['StartDate']?>">
      </div>
      <div class="col s6">
        <label for="disabled">EndDate</label>
        <input name="EndDate" value="<?php echo $row['EndDate']?>" id="disabled" type="date" class="datepicker">
      </div>
    </div>
    <div class="row">
        <div class="col s6">
          <div class="input-field col s12" style="margin-top: 23px">
              <select class="categorey_select">
                  <option value="" disabled selected>Choose Category</option>
                  <?php
                  while($row1 = $result2->fetch_assoc())
                  {?>
                      <option  value="<?php echo $row1['ProjCatID'];?>"><?php echo $row1['ProjCatName'];?></option>
                  <?php }?>
              </select>
              <input type="hidden" id="categorey_input" name="cat" value="<?php echo $row['Category'];?>">
          </div>
        </div>
      <div class="col s6">
        <label for="email">Link</label>
        <input name="Link" id="email" type="text" class="validate"  value="<?php echo $row['Link']?>">
      </div>
    </div>
    <div class="row">
      <div class="col s6">
        <label for="email">Comments</label>
        <textarea style="height: 7rem !important;text-align: left !important;" name="Comments" id="email" type="text" class="validate"  value="<?php echo $row['Comments']?>"></textarea>
      </div>
        <div class="col s6" style="margin-top:57px">
          <label for="email">Investors</label>
          <input name="Investors" id="email" type="text" class="validate"  value="<?php echo $row['Investors']?>">
      </div>
    </div>
      <div class="row">
          <div class="col s6">
              <label for="Percent">Percent</label>
              <input name="Percent" id="Percent" type="text" value="<?php echo $row['PercentRaised']?>">
          </div>
      </div>
    <div class="row">
      <div class="col s12 from_input">
          <button type="submit" class="waves-effect waves-light btn-large form_input" id="form_button">Submit</button>
      </div>
  </div>
  </form>
</div>
  </div>
</div>