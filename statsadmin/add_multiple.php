<div class="csv">
    <div class="col s12 m12 l12">
        <div class="card-panel">
            <div class="row">
                <form class="col s12" method="post" id="add_multiple_form" name="report_csv_from" action="add_multiple_processing" enctype="multipart/form-data">
                    <div>
                        <h6>Upload Csv:</h6>
                    </div>
                    <hr>
                    <p>Csv should be in this format
                        <br>
                        1.IcoName 2.StartDate 3.EndDate 4.TotalUSD 5.Category 6.Link 7.Comments 8.Investors 9 Percent
                    </p>
                    <div id="upload_div">
                        <label>Choose CSV File</label>
                        <input required type="file" name="csv" id="nex_csv" value="upload"/>
                        <button id="upld" type="submit" name="submit" class="waves-effect waves-light btn">Upload</button>
                    </div>
                </form>
            </div>
            <div id='loader' style='margin-left: 403px;margin-top: -36px;display:none'>
                <div class="preloader-wrapper big  active">
                    <div class="spinner-layer spinner-green-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div><div class="gap-patch">
                            <div class="circle"></div>
                        </div><div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>