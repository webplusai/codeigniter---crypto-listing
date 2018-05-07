<?php
    header("Content-type: text/css; charset: UTF-8");
?>

    /* ************************************* */
    /* General                               */
    /* ************************************* */

    body {
      font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
      font-size: 14px;
      margin: 0px;
      padding: 0px;
      background-color: #eee;
      overflow-y: scroll;
    }
    
    
    img {
      padding 0px;
      margin: 0px;
    }
    
    
    a {
      text-decoration: none;
      color: inherit;
    }
    
    
    a:hover 
    {
      color:#77797D; 
      text-decoration:none; 
      cursor:pointer;  
    }
    
  
    hr {
      height: 1px;
      border: 0;
      border-top: 1px solid #e7e7e7;
    }
    
    
    h1 {
      margin: 0px;
    }
    
    
    h2, h3 {
      padding-left: 8px;
      margin: 0px 0px 5px 0px;
      font-size: 1.2em;
    }
    
    .section-heading {
      padding-left: 0px;
      margin: 0px;
    }
    
    
    p {
      margin: 0px;
    }
    
    
    .inline{
      display: inline-block;
      vertical-align:middle;
    }
    
    
    .top {
      vertical-align:top;
    }
    
    
    .spacer {
      padding-top: 35px;
    } 
    
    
    .main {
      margin: 20px;   
    }
    
    
    .container {
      margin: 0 auto;
      max-width: 1800px;
      background-color: #fff;
    }  
    


    /* ************************************* */
    /* Nav                                   */
    /* ************************************* */
     
    .navbar {
        padding: 6px 0px 6px 0px;
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1030;
        border-bottom: solid 1px #e7e7e7;
        background-color: #fff;
    }
    
    
    .nav-container {
      margin: 0px 20px 0px 20px;
    }
    
    
    .logo div {
      display: inline-block;
      vertical-align:middle;
      font-size: 1.7em;
      font-weight: bold;
      color: #000
    }
    
    
    .logo img {
      display: inline-block;
      vertical-align:middle;
      margin-right: 3px;
    }
    
        
    .nav {
      float:right;
      line-height: 32px;
      font-weight: bold;
    } 
    
    
    .nav-item {
      margin-left: 40px;
    }
    
    
    .burger {    
      display: none !important;
      float:right;
      border: 1px solid #e7e7e7;
      border-radius: 5px;
      padding: 4px 10px 4px 10px; 
      cursor: pointer;
    }
    
    
    .burger div {
      width: 22px;
      height: 2px;
      background-color: black;
      margin: 4px 0;
    }
    
    
    .nav-trigger {
      position: absolute;
      clip: rect(0, 0, 0, 0);
    }
    
    
    .nav-trigger:checked ~ .nav, .nav a {
      display: block;
    }
    
            
    @media screen and (max-width: 1000px) {

      .nav {
        list-style: none;
        background: #fff;
        width: 100px;
        position: fixed;
        right: 0;
        z-index: 99;
        padding: 5px 5px 5px 20px;
        margin-top: 7px;
        display: none;
        border: 1px solid #e7e7e7;
        border-top: 0px; border-right: 0px;  
      }
      
      
      .nav-item {
        display: block;
        margin: 0px;
      }
      
      
      .burger {
        display: inline-block !important;
      }

    }
    
    
    /* ************************************* */
    /* Heading                               */
    /* ************************************* */ 
    
    .header {
      text-align: center;
      padding-top: 50px;
      margin: 0 auto;
      background: #fff;
    }
    
    
    .banner {
      height: 95px;
    }
    
    
    
    /* ************************************* */
    /* Footer                                */
    /* ************************************* */    
    
       
    .footer {
      padding: 7px 30px 7px 30px;
      background-color: #333;
      color:#D5D5D5;
    }
    
    
    .footer-col {
      padding: 0px 40px 20px 0px;
    }
    
    
    .footer h4 {
      border-bottom: 2px solid #0FBE7C;
      padding-bottom: 5px;
      margin-bottom: 10px; 
    }
    
    
    .footer a {
      font-size: 0.9em;
      display:block; 
      border-bottom: 1px solid #444444;
      padding-top: 7px;
      padding-bottom: 7px;
      width: 180px;
    }
    
    
    .footer a:hover {
      color: #0FBE7C;
    }
    
    
    .copyright {
      font-size: 0.9em;
      padding: 10px;
    }
    
    
      
    /* ************************************* */
    /* Live ICOs                             */
    /* ************************************* */
    
    .icobox {
      cursor: pointer;
      cursor: hand;
      vertical-align:bottom;
      display: inline-block;
      position: relative;
      padding: 0px;
    }
    
    .icobox h4 {
      margin: 2px 0px 0px 0px;
    }
    
    .icobox-text {
      line-height: 1.2;
    }
    
    .gold {
      background-image: url(https://www.coinschedule.com/img/goldplate.png);
      margin: 0px 2px 5px 0px;
      width: 209px;
      height: 150px;
      text-align: center;
      border: #F7A61C 2px solid;
      font-weight: bold;
    }
    
    .gold h4 {
      font-weight: 700;
    }

    .gold .icobox-text {
      position: relative;
      top: 40%;
      transform: translateY(-50%);
      font-size: 18px;
      padding: 0px 2px 0px 2px;
    }
    
    .gold .done {
      font-size: 0.8em;
      padding-top: 2px;
    }
    
    .silver {
      background-image: url(https://www.coinschedule.com/img/silverplate.png);
      margin: 0px 2px 5px 0px;
      width: 209px;
      height: 80px;
      border: #A9A9A9 2px solid;
    }    
    
    .silver img {
      padding: 6px 3px 0px 3px;
      display: inline-block;
    }
    
    .silver .icobox-text {
      display: inline-block;
      position: relative;
      width: 140px;
      top: 35%;
      transform: translateY(-50%);
      vertical-align:top;
    }
    
    .silver h4 {
      font-weight: 600;
    }
    
    .silver .done {
      font-size: 0.9em;
      color: #000;  
    }
    
    .standard {
      color: #34495e;
      width: 135px;
      height: 68px;
      text-align: center;
      margin: 0px 3px 5px 0px;
      border: 1px solid;
      border-radius: 3px;
      font-size: 0.9em;
      font-weight: normal;
    }
    
        
    .standard .category {
      height: 16px;
      font-size: 0.9em;
    }
    
    .standard .icobox-text {
      position: relative;
      top: 35%;
      transform: translateY(-50%);
    }
    
    .standard h4 {
      font-weight: 600;
    }
    
    .standard .done {
      font-size: 0.9em;
      color: #a7afb8;  
    }
    
    .category {
      position: absolute;
      bottom: 0; 
      width: 100%;
      text-align: center;
      height: 20px;
      background-color: #5E4FA0;
      font-weight: normal;
      color: #fff; 
    }
    
    .red {
      color:red !important;  
    }
    
    .green {
      color:green !important;  
    }
    
    
    
    /* ************************************* */
    /* Upcoming ICOs                         */
    /* ************************************* */
    
    .upcoming-table {
      border: 1px solid #ddd; 
      border-collapse: collapse;
      width:100%;
    }
     
    .upcoming-table>tbody>tr:nth-child(odd) {
      background-color: #EAF3F3;
    }
    
    .upcoming-table>tbody>tr>td {
      padding: 6px;
      line-height: 1.42857143;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }
    
    .upcoming-table tr:hover td{
      background-color: #D9E4E6;
      cursor: pointer;
    }



    /* ************************************* */
    /* Project                               */
    /* ************************************* */
      
    .sponsor {
      padding-top: 7px;
    }  
    
    
    .project-logo { 
      padding-right: 5px;
    }
    
    
    .project-heading {
      width: calc(100% - 180px);
    }  
    
         
    .project-desc {     
      margin: 15px 0px 15px 10px;
    }
    
    
    .project-info-container {                           
      margin-left: 10px;
    }
    
    
    .project-info {
      margin: 10px 20px 10px 0px;
      width: 120px;
    }
    
    
    .project-info-lbl {
      font-weight: bold;
    }
    
    
    .project-links {
      width: 100%;
      margin: 0px 0px 0px 10px;
    }
    
    
    .project-link {
      display: inline-block;
      margin: 5px 0px 5px 0px;
    }
    
    .project-link a {
      width: 120px;
      display: block;
    }
    
    
    .project-link img {
      padding-right: 4px;
    }
    

   
   
    /* ************************************* */
    /* Rank Meter                            */
    /* ************************************* */
    
    .rank-meter {
      font-size: 0;
      position:relative;
      background-color: #e7e7e7;
      height: 25px;
    }
    
    
    .project-rank-lbl {
      font-weight: bold;
      font-size: 0.9em;
    }
    
    
    .project-rank-lbl table {
      width: 100%;
      border-collapse: collapse;
    }
    
    
    .project-rank-score {
      font-weight: bold;
      font-size: 1.4em;
    }
    
    
    .rank-pointer {
      border-right: solid 0px black;
      height:25px;
      position:absolute;
      left: 0;  
    }
    
    
    .rank-pointer img {
      float: right;
      margin-right: -5px;
    }
    
    
    .rank {
      width: 35px;
      height: 20px; 
      margin-right: 0px;
    }
    
    
    .one {
      background-color: #C0504D;
    }
    
    
    .two {
      background-color: #F79646;
    }
    
    
    .three {
      background-color: #FFFF00;
    }
    
    
    .four {
      background-color: #9BBB59;
    }
    
    
    .five {
      background-color: #00B050;
    }
    
    

    /* ************************************* */
    /* Team                                  */
    /* ************************************* */
    
    .project-team {
      margin: 0px 0px 20px 0px;
    }
    
    
    .team-member {
      border: 1px solid #e7e7e7;
      border-radius: 2px;
      margin: 4px 4px 4px 0px;
      padding: 10px 10px 5px 10px;
      width: 250px;
    }
    
    
    .team-pic {
      border:1px solid #;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px; 
      width: 100px;
      height: 100px;
    }
    
    
    .team-info {
      margin-left: 5px;
      width: 50%;
    }
    
    .team-nopic {
      width: 100%;  
    }
    
    
    .team-txt {
      height:83px;
    }
    
    
    .team-name {
      font-weight: bold;
    }
    
    
    .team-position {
      font-size: 0.9em;
      color: #34495e;
      margin-bottom: 3px;
    }
    
    
    @media screen and (max-width: 600px){
      .nav-container {
        margin: 0px 10px 0px 10px;   
      }
      
      .main {
        margin: 20px 10px 20px 10px;   
      } 
     
     
      .project-heading {
        width: 100%;
      }
      
      
      .project-desc {     
        margin: 10px 0px 10px 3px;
      }
      
      
      .project-info-container {
        margin-left: 3px;
      }
     
      
      .project-links {
        margin: 0px 0px 0px 3px;
      }
      
      
      .team-member {
        width: 93%;
      }
      
      
      .team-info {
        width: 60%;
      }
    }
    


