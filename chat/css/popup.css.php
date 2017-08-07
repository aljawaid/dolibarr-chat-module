<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    css/mycss.css.php
 * \ingroup mymodule
 * \brief   Example CSS.
 *
 * Put detailed description here.
 */

// TODO : optimise this css file (remove not used class/code, & avoid conflits with chat.css file)

// Load Dolibarr environment
global $mod_path;
$mod_path = "";
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
	$mod_path = "/custom";
}

header('Content-Type: text/css');

$is_chat_index_page = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) == DOL_URL_ROOT.$mod_path.'/chat/index.php' ? true : false;

if (! $is_chat_index_page)
{

?>

#chat_popup {
    position: fixed;
    bottom: 0px;
    right: 0px;
    margin-right: 15px;
    width: 30%;
}

.panel-body {
    overflow-y: scroll;
    height: 250px;
}

#accordion {
    cursor: pointer;
}

/*---- Bootstrap ----*/

.panel {
    /*margin-bottom: 20px;*/
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}

.panel-default {
    border-color: #ddd;
}

.panel-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}

.panel-default > .panel-heading {
    color: #333;
    background-color: #f5f5f5;
    border-color: #ddd;
}

.btn-group, .btn-group-vertical {
    position: relative;
    display: inline-block;
    vertical-align: middle;
}

.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}

.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}

.btn-primary {
    color: #fff;
    background-color: #428bca;
    border-color: #357ebd;
}

.btn-xs, .btn-group-xs > .btn {
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;
}

.btn-sm, .btn-group-sm > .btn {
    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
}

.pull-right {
    float: right !important;
}

.collapse.in {
    display: block;
}

.collapse {
    display: none;
}

.panel-body {
    padding: 15px;
}

.panel-footer {
    padding: 10px 15px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
}

.panel-footer .btn:hover, .panel-heading .btn:hover
{
    color:#666;
    /*background: #f8f8f8;*/
    background: #FBF9FA;
    text-decoration: none;
}

.panel-footer .btn:active, .panel-footer .btn:hover, .panel-footer .btn:focus
{
    box-shadow: none;
}

.input-group {
    position: relative;
    display: table;
    border-collapse: separate;
}

.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.form-control:focus {
    border-color: #66AFE9;
    outline: 0px none;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(102, 175, 233, 0.6);
}

.form-control:-moz-placeholder{
    color:#999
}

.form-control::-moz-placeholder{
    color:#999;
    opacity:1
}

.form-control:-ms-input-placeholder{
    color:#999
}

.form-control::-webkit-input-placeholder{
    color:#999
}

.form-control[disabled],.form-control[readonly],fieldset[disabled] .form-control{
    cursor:not-allowed;
    background-color:#eee
}

.input-sm, .form-horizontal .form-group-sm .form-control {
    height: 30px;
    padding: 5px 10px;
    font-size: 13px;
    line-height: 1.5;
    border-radius: 3px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

.input-group .form-control {
    position: relative;
    z-index: 2;
    float: left;
    width: 100%;
    margin-bottom: 0;
}

.input-group-addon, .input-group-btn, .input-group .form-control {
    display: table-cell;
}

.input-group-btn > .btn {
    position: relative;
}

.input-group-addon, .input-group-btn {
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;
}

.input-group-btn:last-child > .btn, .input-group-btn:last-child > .btn-group > .btn, .input-group-btn:last-child > .dropdown-toggle, .input-group-btn:first-child > .btn:not(:first-child), .input-group-btn:first-child > .btn-group:not(:first-child) > .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.input-group-btn:last-child > .btn, .input-group-btn:last-child > .btn-group {
    margin-left: -1px;
}

.pull-right {
    float: right;
}

.pull-left {
    float: left;
}

.media > .pull-left {
    margin-right: 10px;
}

.media, .media-body {
    overflow: hidden;
}

.media-heading {
    margin: 0px 0px 5px;
    font-size: 14px;
}

small, .small {
    font-size: 85%;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

/*---- end of Bootstrap ----*/

/*---- dropdown ----*/

.dropbtn {
    margin-left: 5px;
    margin-bottom: 5px;
    display: inline-block;
    cursor: pointer;
}

.dropdown, .dropdown-click {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 100px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 9999;
}

.dropdown-right {
    right: 0;
}

.dropdown-top {
    bottom: 100%;
}

.more-width {
    min-width: 140px;
}

.cursor-pointer {
    cursor: pointer;
}

.dropdown-image {
    max-width: 300px;
    max-height: 200px;
}

.drop-btn {
    padding: 8px 12px;
}

.dropdown-content a, .dropdown-content div {
    color: #555;
    font-size: 13px;
    padding: 8px 12px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover, .dropdown-content div:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}

.show {
    display: block;
}

/*---- end dropdown ----*/

.align-middle {
    vertical-align: middle;
}

.hidden {
    display: none;
    visibility: hidden;
}

#custom-search-input {
    background: #F5F3F3;
    margin: 0px;
    padding: 10px;
}

#custom-search-input a {
    margin-right: 2px;
    margin-top: 7px;
    padding: 2px 5px;
    position: absolute;
    right: 0px;
    top: 0px;
    z-index: 9999;
}

#custom-search-input .search-query {
    padding-right: 30px;
}

#users_container {
    overflow: auto;
}

.btn-icon, .smiley {
    vertical-align: middle;
}

.btn-small-icon {
    width: 11px;
}

#smiley-dropdown td img {
    padding: 10px;
    cursor: pointer;
}

#smiley-dropdown td:hover {background-color: #f1f1f1}

#private-msg-to-user {
    margin-bottom: 10px;
}

#private-msg-to-user span {
    font-weight: bold;
    color: #003BB3;
}

#private-msg-textarea {
    height: 60px;
}

.private-msg {
    background: #FBF9FA;
    border-radius: 20px;
    border: 1px solid #f3f3f3;
}

.msg-text {
    width: 85%;
    padding-right: 15px;
    padding-left: 15px;
    float: left;
}

.msg-attachment {
    padding: 5px;
    display: inline-block;
    border-radius: 4px;
    background: #F5F3F3;
    /*margin-left: 15px;*/
    margin-top: 5px;
    border: 1px solid #eee;
}

.msg-attachment a {
    color: #555;
    font-size: 13px;
    display: inline-block;
    word-break: break-all;
}

.conversation
{
    padding:5px;
    border-bottom:1px solid #ddd;
    margin:0;
    font-size: 14px;

}

.message-wrap
{
    box-shadow: 0 0 3px #ddd;
    padding:0;

}
.msg
{
    padding:10px;
    /*border-bottom:1px solid #ddd;*/
    /*margin:0;*/
    margin-bottom: 10px;
    overflow: visible;
}
.msg-wrap
{
    padding:10px;
    font-size: 15px;
}

.time
{
    color:#bfbfbf;
}

#chatForm
{
    position: absolute;
    left: 0px;
    bottom: 0px;
    width: 100%;
}

.send-wrap
{
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding:10px;
    /*background: #f8f8f8;*/
    background: #FBF9FA;
}

.send-message
{
    resize: none;
}

.highlight
{
    background-color: #f7f7f9;
    border: 1px solid #e1e1e8;
}

.send-message-btn
{
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    width: 30%;
}

.btn-panel .btn
{
    color:#b8b8b8;
    outline: none;
    overflow: hidden;
    transition: 0.2s all ease-in-out;
}

.btn-panel .btn:hover
{
    color:#666;
    /*background: #f8f8f8;*/
    background: #FBF9FA;
    text-decoration: none;
}
.btn-panel .btn:active
{
    background: #f8f8f8;
    box-shadow: 0 0 1px #ddd;
}

.btn-panel-conversation .btn,.btn-panel-msg .btn
{

    background: #f8f8f8;
}
.btn-panel-conversation .btn:first-child
{
    border-right: 1px solid #ddd;
}

.msg-wrap .media-heading, .media-heading a
{
    color:#003bb3;
    font-weight: bold;
}

.media-heading a {
    text-decoration: none;
}


.msg-date
{
    background: none;
    text-align: center;
    color:#aaa;
    border:none;
    box-shadow: none;
    border-bottom: 1px solid #ddd;
}


body::-webkit-scrollbar {
    width: 12px;
}


/* Let's get this party started */
::-webkit-scrollbar {
    width: 6px;
}

/* Track */
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
/*        -webkit-border-radius: 10px;
    border-radius: 10px;*/
}

/* Handle */
::-webkit-scrollbar-thumb {
/*        -webkit-border-radius: 10px;
    border-radius: 10px;*/
    background:#ddd; 
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}
::-webkit-scrollbar-thumb:window-inactive {
    background: #ddd; 
}

<?php

} // fin if (! $is_chat_index_page)
