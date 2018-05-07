<?php
/**
 * Created by PhpStorm.
 * User: Haisam07
 * Date: 1/23/2018
 * Time: 12:11 AM
 */
include  'database.php';
$sql = 'SELECT ID,ICOName,StartDate,EndDate,TotalUSD,ProjCatName,Link,PercentRaised FROM tbl_icohistory as tih
        left join tbl_project_categories as tpc on tpc.ProjCatID=tih.Category
        order by tih.EndDate DESC';
$result = $conn->query($sql);
?>
<div class="">
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <div class="row">
                    <div class="row">
                        <table id="accounts_report_table" class="mdl-data-table datatable_report" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th data-field="name">Name</th>
                                <th data-field="name">StartDate</th>
                                <th data-field="name">EndDate</th>
                                <th data-field="name">Total USD Raised</th>
                                <th data-field="name">Category</th>
                                <th data-field="name">Percent</th>
                                <th data-field="name">Link</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            while($row = $result->fetch_assoc())
                            {
                                ?>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col s4">
                                                <?php echo $row['ICOName']?>
                                            </div>
                                            <div class="col s4">
                                                <span><a class="jn_detail rm" href="javascript:" data-url="edit.php" name="<?php echo $row['ID']?>"><i class="material-icons">edit</i></a></span>
                                            </div>
                                            <div class="col s4">
                                                <span><a class="remove" href="javascript:" data-url="delete.php" name="<?php echo $row['ID']?>"><i class="material-icons">delete</i></a></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $row['StartDate']?></td>
                                    <td><?php echo $row['EndDate']?></td>
                                    <td><?php echo $row['TotalUSD']?></td>
                                    <td><?php echo $row['ProjCatName']?></td>
                                    <td><?php echo $row['PercentRaised']?></td>
                                    <td><a href="<?php echo $row['Link'];?>" target="_blank">Link</a></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_content">
        
    </div>
    <div id="dialog-confirm">
        
    </div>
</div>