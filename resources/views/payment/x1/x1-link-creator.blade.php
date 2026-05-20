<!doctype html>
<html lang="en">
  <head>
    <title>Payment Link Provider</title>
    <link rel="icon" href="{{ asset('images/Rayzen-Pay-logo.png') }}" type="image/x-icon">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- font awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container" style="background-color: aliceblue; height: 100vh;">
        <div class="text-center pt-5">
            <h3><img src="{{ asset('images/Rayzen-Pay-logo.png') }}" height="60px">Payment Link Provider</h3>
        </div>
        <div class="my-3">
            <form action="{{ route('paymentLinkX1') }}" method="post" class="">
                @csrf
                <div class="p-2 my-3 align-iems-center">
                    <label for="amount" class="form-label">Enter the amount to create payment link:</label>
                    <input type="number" class="form-control" step="0.01" name="amount" id="amount" placeholder="in EUROs" required>
                </div>
                <div class="p-2 mb-3 align-iems-center">
                    <label for="email" class="form-label">Enter email:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Payer's email Address" required>
                </div>
                <div class="p-2 mb-3 align-iems-center">
                    <label for="first_name" class="form-label">Enter First Name:</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Payer's First Name" required>
                </div>
                <div class="p-2 mb-3 align-iems-center">
                    <label for="last_name" class="form-label">Enter Last Name:</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Payer's Last Name" required>
                </div>
                <div class="p-2 mb-3 align-iems-center">
                    <div class="form-group">
                      <label for="nationality" class="form-label">Select Nationality:</label>
                      <select required="required" id="nationality" name="nationality" class="form-control">
                        <option class="bg-white text-alpha-700" value="ALB">Albania</option>
                        <option class="bg-white text-alpha-700" value="DZA">Algeria</option>
                        <option class="bg-white text-alpha-700" value="AND">Andorra</option>
                        <option class="bg-white text-alpha-700" value="AGO">Angola</option>
                        <option class="bg-white text-alpha-700" value="AIA">Anguilla</option>
                        <option class="bg-white text-alpha-700" value="ATG">Antigua and Barbuda</option>
                        <option class="bg-white text-alpha-700" value="ARG">Argentina</option>
                        <option class="bg-white text-alpha-700" value="ARM">Armenia</option>
                        <option class="bg-white text-alpha-700" value="AUS">Australia</option>
                        <option class="bg-white text-alpha-700" value="AUT">Austria</option>
                        <option class="bg-white text-alpha-700" value="AZE">Azerbaijan</option>
                        <option class="bg-white text-alpha-700" value="BHS">Bahamas</option>
                        <option class="bg-white text-alpha-700" value="BHR">Bahrain</option>
                        <option class="bg-white text-alpha-700" value="BGD">Bangladesh</option>
                        <option class="bg-white text-alpha-700" value="BRB">Barbados</option>
                        <option class="bg-white text-alpha-700" value="BEL">Belgium</option>
                        <option class="bg-white text-alpha-700" value="BLZ">Belize</option>
                        <option class="bg-white text-alpha-700" value="BEN">Benin</option>
                        <option class="bg-white text-alpha-700" value="BMU">Bermuda</option>
                        <option class="bg-white text-alpha-700" value="BTN">Bhutan</option>
                        <option class="bg-white text-alpha-700" value="BOL">Bolivia</option>
                        <option class="bg-white text-alpha-700" value="BIH">Bosnia and Herzegovina</option>
                        <option class="bg-white text-alpha-700" value="BWA">Botswana</option>
                        <option class="bg-white text-alpha-700" value="BVT">Bouvet Island</option>
                        <option class="bg-white text-alpha-700" value="BRA">Brazil</option>
                        <option class="bg-white text-alpha-700" value="IOT">British Indian Ocean Territory</option>
                        <option class="bg-white text-alpha-700" value="BRN">Brunei Darussalam</option>
                        <option class="bg-white text-alpha-700" value="BGR">Bulgaria</option>
                        <option class="bg-white text-alpha-700" value="KHM">Cambodia</option>
                        <option class="bg-white text-alpha-700" value="CPV">Cape Verde</option>
                        <option class="bg-white text-alpha-700" value="CYM">Cayman Islands</option>
                        <option class="bg-white text-alpha-700" value="CHL">Chile</option>
                        <option class="bg-white text-alpha-700" value="CHN">China</option>
                        <option class="bg-white text-alpha-700" value="CXR">Christmas Island</option>
                        <option class="bg-white text-alpha-700" value="CCK">Cocos (Keeling) Islands</option>
                        <option class="bg-white text-alpha-700" value="COL">Colombia</option>
                        <option class="bg-white text-alpha-700" value="COK">Cook Islands</option>
                        <option class="bg-white text-alpha-700" value="CRI">Costa Rica</option>
                        <option class="bg-white text-alpha-700" value="HRV">Croatia (Hrvatska)</option>
                        <option class="bg-white text-alpha-700" value="CYP">Cyprus</option>
                        <option class="bg-white text-alpha-700" value="CZE">Czech Republic</option>
                        <option class="bg-white text-alpha-700" value="DNK">Denmark</option>
                        <option class="bg-white text-alpha-700" value="DJI">Djibouti</option>
                        <option class="bg-white text-alpha-700" value="DMA">Dominica</option>
                        <option class="bg-white text-alpha-700" value="DOM">Dominican Republic</option>
                        <option class="bg-white text-alpha-700" value="ECU">Ecuador</option>
                        <option class="bg-white text-alpha-700" value="EGY">Egypt</option>
                        <option class="bg-white text-alpha-700" value="SLV">El Salvador</option>
                        <option class="bg-white text-alpha-700" value="GNQ">Equatorial Guinea</option>
                        <option class="bg-white text-alpha-700" value="EST">Estonia</option>
                        <option class="bg-white text-alpha-700" value="ETH">Ethiopia</option>
                        <option class="bg-white text-alpha-700" value="FLK">Falkland Islands (Malvinas)</option>
                        <option class="bg-white text-alpha-700" value="FRO">Faroe Islands</option>
                        <option class="bg-white text-alpha-700" value="FJI">Fiji</option>
                        <option class="bg-white text-alpha-700" value="FIN">Finland</option>
                        <option class="bg-white text-alpha-700" value="FRA">France</option>
                        <option class="bg-white text-alpha-700" value="FXX">France, Metropolitan</option>
                        <option class="bg-white text-alpha-700" value="GUF">French Guiana</option>
                        <option class="bg-white text-alpha-700" value="ATF">French Southern Territories</option>
                        <option class="bg-white text-alpha-700" value="GAB">Gabon</option>
                        <option class="bg-white text-alpha-700" value="GEO">Georgia</option>
                        <option class="bg-white text-alpha-700" value="DEU">Germany</option>
                        <option class="bg-white text-alpha-700" value="GHA">Ghana</option>
                        <option class="bg-white text-alpha-700" value="GIB">Gibraltar</option>
                        <option class="bg-white text-alpha-700" value="GGY">Guernsey</option>
                        <option class="bg-white text-alpha-700" value="GRC">Greece</option>
                        <option class="bg-white text-alpha-700" value="GRL">Greenland</option>
                        <option class="bg-white text-alpha-700" value="GRD">Grenada</option>
                        <option class="bg-white text-alpha-700" value="GLP">Guadeloupe</option>
                        <option class="bg-white text-alpha-700" value="GTM">Guatemala</option>
                        <option class="bg-white text-alpha-700" value="GIN">Guinea</option>
                        <option class="bg-white text-alpha-700" value="GUY">Guyana</option>
                        <option class="bg-white text-alpha-700" value="HMD">Heard and Mc Donald Islands</option>
                        <option class="bg-white text-alpha-700" value="HND">Honduras</option>
                        <option class="bg-white text-alpha-700" value="HKG">Hong Kong</option>
                        <option class="bg-white text-alpha-700" value="HUN">Hungary</option>
                        <option class="bg-white text-alpha-700" value="ISL">Iceland</option>
                        <option class="bg-white text-alpha-700" value="IND">India</option>
                        <option class="bg-white text-alpha-700" value="IMN">Isle of Man</option>
                        <option class="bg-white text-alpha-700" value="IDN">Indonesia</option>
                        <option class="bg-white text-alpha-700" value="IRL">Ireland</option>
                        <option class="bg-white text-alpha-700" value="ISR">Israel</option>
                        <option class="bg-white text-alpha-700" value="ITA">Italy</option>
                        <option class="bg-white text-alpha-700" value="JEY">Jersey</option>
                        <option class="bg-white text-alpha-700" value="JAM">Jamaica</option>
                        <option class="bg-white text-alpha-700" value="JOR">Jordan</option>
                        <option class="bg-white text-alpha-700" value="KAZ">Kazakhstan</option>
                        <option class="bg-white text-alpha-700" value="KEN">Kenya</option>
                        <option class="bg-white text-alpha-700" value="KOR">Korea, Republic of</option>
                        <option class="bg-white text-alpha-700" value="XKX">Kosovo</option>
                        <option class="bg-white text-alpha-700" value="KWT">Kuwait</option>
                        <option class="bg-white text-alpha-700" value="KGZ">Kyrgyzstan</option>
                        <option class="bg-white text-alpha-700" value="LVA">Latvia</option>
                        <option class="bg-white text-alpha-700" value="LSO">Lesotho</option>
                        <option class="bg-white text-alpha-700" value="LIE">Liechtenstein</option>
                        <option class="bg-white text-alpha-700" value="LTU">Lithuania</option>
                        <option class="bg-white text-alpha-700" value="LUX">Luxembourg</option>
                        <option class="bg-white text-alpha-700" value="MAC">Macau</option>
                        <option class="bg-white text-alpha-700" value="MKD">Macedonia</option>
                        <option class="bg-white text-alpha-700" value="MDG">Madagascar</option>
                        <option class="bg-white text-alpha-700" value="MWI">Malawi</option>
                        <option class="bg-white text-alpha-700" value="MYS">Malaysia</option>
                        <option class="bg-white text-alpha-700" value="MDV">Maldives</option>
                        <option class="bg-white text-alpha-700" value="MLT">Malta</option>
                        <option class="bg-white text-alpha-700" value="MTQ">Martinique</option>
                        <option class="bg-white text-alpha-700" value="MRT">Mauritania</option>
                        <option class="bg-white text-alpha-700" value="MUS">Mauritius</option>
                        <option class="bg-white text-alpha-700" value="MYT">Mayotte</option>
                        <option class="bg-white text-alpha-700" value="MEX">Mexico</option>
                        <option class="bg-white text-alpha-700" value="MDA">Moldova, Republic of</option>
                        <option class="bg-white text-alpha-700" value="MCO">Monaco</option>
                        <option class="bg-white text-alpha-700" value="MNG">Mongolia</option>
                        <option class="bg-white text-alpha-700" value="MNE">Montenegro</option>
                        <option class="bg-white text-alpha-700" value="MSR">Montserrat</option>
                        <option class="bg-white text-alpha-700" value="MAR">Morocco</option>
                        <option class="bg-white text-alpha-700" value="NRU">Nauru</option>
                        <option class="bg-white text-alpha-700" value="NPL">Nepal</option>
                        <option class="bg-white text-alpha-700" value="NCL">New Caledonia</option>
                        <option class="bg-white text-alpha-700" value="NZL">New Zealand</option>
                        <option class="bg-white text-alpha-700" value="NIC">Nicaragua</option>
                        <option class="bg-white text-alpha-700" value="NIU">Niue</option>
                        <option class="bg-white text-alpha-700" value="NFK">Norfolk Island</option>
                        <option class="bg-white text-alpha-700" value="NOR">Norway</option>
                        <option class="bg-white text-alpha-700" value="OMN">Oman</option>
                        <option class="bg-white text-alpha-700" value="PAK">Pakistan</option>
                        <option class="bg-white text-alpha-700" value="PLW">Palau</option>
                        <option class="bg-white text-alpha-700" value="PAN">Panama</option>
                        <option class="bg-white text-alpha-700" value="PRY">Paraguay</option>
                        <option class="bg-white text-alpha-700" value="PER">Peru</option>
                        <option class="bg-white text-alpha-700" value="PHL">Philippines</option>
                        <option class="bg-white text-alpha-700" value="PCN">Pitcairn</option>
                        <option class="bg-white text-alpha-700" value="POL">Poland</option>
                        <option class="bg-white text-alpha-700" value="PRT">Portugal</option>
                        <option class="bg-white text-alpha-700" value="QAT">Qatar</option>
                        <option class="bg-white text-alpha-700" value="REU">Reunion</option>
                        <option class="bg-white text-alpha-700" value="ROU">Romania</option>
                        <option class="bg-white text-alpha-700" value="RWA">Rwanda</option>
                        <option class="bg-white text-alpha-700" value="KNA">Saint Kitts and Nevis</option>
                        <option class="bg-white text-alpha-700" value="LCA">Saint Lucia</option>
                        <option class="bg-white text-alpha-700" value="VCT">Saint Vincent and the Grenadines</option>
                        <option class="bg-white text-alpha-700" value="WSM">Samoa</option>
                        <option class="bg-white text-alpha-700" value="SMR">San Marino</option>
                        <option class="bg-white text-alpha-700" value="STP">Sao Tome and Principe</option>
                        <option class="bg-white text-alpha-700" value="SAU">Saudi Arabia</option>
                        <option class="bg-white text-alpha-700" value="SEN">Senegal</option>
                        <option class="bg-white text-alpha-700" value="SRB">Serbia</option>
                        <option class="bg-white text-alpha-700" value="SYC">Seychelles</option>
                        <option class="bg-white text-alpha-700" value="SLE">Sierra Leone</option>
                        <option class="bg-white text-alpha-700" value="SGP">Singapore</option>
                        <option class="bg-white text-alpha-700" value="SVK">Slovakia</option>
                        <option class="bg-white text-alpha-700" value="SVN">Slovenia</option>
                        <option class="bg-white text-alpha-700" value="ZAF">South Africa</option>
                        <option class="bg-white text-alpha-700" value="SGS">South Georgia South Sandwich Islands</option>
                        <option class="bg-white text-alpha-700" value="ESP">Spain</option>
                        <option class="bg-white text-alpha-700" value="LKA">Sri Lanka</option>
                        <option class="bg-white text-alpha-700" value="SHN">St. Helena</option>
                        <option class="bg-white text-alpha-700" value="SPM">St. Pierre and Miquelon</option>
                        <option class="bg-white text-alpha-700" value="SUR">Suriname</option>
                        <option class="bg-white text-alpha-700" value="SJM">Svalbard and Jan Mayen Islands</option>
                        <option class="bg-white text-alpha-700" value="SWZ">Swaziland</option>
                        <option class="bg-white text-alpha-700" value="SWE">Sweden</option>
                        <option class="bg-white text-alpha-700" value="CHE">Switzerland</option>
                        <option class="bg-white text-alpha-700" value="TWN">Taiwan</option>
                        <option class="bg-white text-alpha-700" value="TZA">Tanzania, United Republic of</option>
                        <option class="bg-white text-alpha-700" value="THA">Thailand</option>
                        <option class="bg-white text-alpha-700" value="TGO">Togo</option>
                        <option class="bg-white text-alpha-700" value="TKL">Tokelau</option>
                        <option class="bg-white text-alpha-700" value="TON">Tonga</option>
                        <option class="bg-white text-alpha-700" value="TTO">Trinidad and Tobago</option>
                        <option class="bg-white text-alpha-700" value="TUN">Tunisia</option>
                        <option class="bg-white text-alpha-700" value="TUR">Turkey</option>
                        <option class="bg-white text-alpha-700" value="TKM">Turkmenistan</option>
                        <option class="bg-white text-alpha-700" value="TCA">Turks and Caicos Islands</option>
                        <option class="bg-white text-alpha-700" value="UGA">Uganda</option>
                        <option class="bg-white text-alpha-700" value="UKR">Ukraine</option>
                        <option class="bg-white text-alpha-700" value="ARE">United Arab Emirates</option>
                        <option class="bg-white text-alpha-700" value="GBR">United Kingdom</option>
                        <option class="bg-white text-alpha-700" value="URY">Uruguay</option>
                        <option class="bg-white text-alpha-700" value="UZB">Uzbekistan</option>
                        <option class="bg-white text-alpha-700" value="VUT">Vanuatu</option>
                        <option class="bg-white text-alpha-700" value="VAT">Vatican City State</option>
                        <option class="bg-white text-alpha-700" value="VNM">Vietnam</option>
                        <option class="bg-white text-alpha-700" value="VGB">Virgin Islands (British)</option>
                        <option class="bg-white text-alpha-700" value="WLF">Wallis and Futuna Islands</option>
                        <option class="bg-white text-alpha-700" value="ZMB">Zambia</option>
                        <option class="bg-white text-alpha-700" value="CUW">countries.cw</option>
                      </select>
                    </div>
                </div>
                <div class="p-2 mb-3 align-iems-center">
                    <div class="form-group">
                        <label for="country_of_residence" class="form-label">Select Country Of Residence:</label>
                        <select required="required" id="country_of_residence" name="country_of_residence" class="form-control">
                            <option class="bg-white text-alpha-700" value="ALB">Albania</option>
                            <option class="bg-white text-alpha-700" value="DZA">Algeria</option>
                            <option class="bg-white text-alpha-700" value="AND">Andorra</option>
                            <option class="bg-white text-alpha-700" value="AGO">Angola</option>
                            <option class="bg-white text-alpha-700" value="AIA">Anguilla</option>
                            <option class="bg-white text-alpha-700" value="ATG">Antigua and Barbuda</option>
                            <option class="bg-white text-alpha-700" value="ARG">Argentina</option>
                            <option class="bg-white text-alpha-700" value="ARM">Armenia</option>
                            <option class="bg-white text-alpha-700" value="AUS">Australia</option>
                            <option class="bg-white text-alpha-700" value="AUT">Austria</option>
                            <option class="bg-white text-alpha-700" value="AZE">Azerbaijan</option>
                            <option class="bg-white text-alpha-700" value="BHS">Bahamas</option>
                            <option class="bg-white text-alpha-700" value="BHR">Bahrain</option>
                            <option class="bg-white text-alpha-700" value="BGD">Bangladesh</option>
                            <option class="bg-white text-alpha-700" value="BRB">Barbados</option>
                            <option class="bg-white text-alpha-700" value="BEL">Belgium</option>
                            <option class="bg-white text-alpha-700" value="BLZ">Belize</option>
                            <option class="bg-white text-alpha-700" value="BEN">Benin</option>
                            <option class="bg-white text-alpha-700" value="BMU">Bermuda</option>
                            <option class="bg-white text-alpha-700" value="BTN">Bhutan</option>
                            <option class="bg-white text-alpha-700" value="BOL">Bolivia</option>
                            <option class="bg-white text-alpha-700" value="BIH">Bosnia and Herzegovina</option>
                            <option class="bg-white text-alpha-700" value="BWA">Botswana</option>
                            <option class="bg-white text-alpha-700" value="BVT">Bouvet Island</option>
                            <option class="bg-white text-alpha-700" value="BRA">Brazil</option>
                            <option class="bg-white text-alpha-700" value="IOT">British Indian Ocean Territory</option>
                            <option class="bg-white text-alpha-700" value="BRN">Brunei Darussalam</option>
                            <option class="bg-white text-alpha-700" value="BGR">Bulgaria</option>
                            <option class="bg-white text-alpha-700" value="KHM">Cambodia</option>
                            <option class="bg-white text-alpha-700" value="CPV">Cape Verde</option>
                            <option class="bg-white text-alpha-700" value="CYM">Cayman Islands</option>
                            <option class="bg-white text-alpha-700" value="CHL">Chile</option>
                            <option class="bg-white text-alpha-700" value="CHN">China</option>
                            <option class="bg-white text-alpha-700" value="CXR">Christmas Island</option>
                            <option class="bg-white text-alpha-700" value="CCK">Cocos (Keeling) Islands</option>
                            <option class="bg-white text-alpha-700" value="COL">Colombia</option>
                            <option class="bg-white text-alpha-700" value="COK">Cook Islands</option>
                            <option class="bg-white text-alpha-700" value="CRI">Costa Rica</option>
                            <option class="bg-white text-alpha-700" value="HRV">Croatia (Hrvatska)</option>
                            <option class="bg-white text-alpha-700" value="CYP">Cyprus</option>
                            <option class="bg-white text-alpha-700" value="CZE">Czech Republic</option>
                            <option class="bg-white text-alpha-700" value="DNK">Denmark</option>
                            <option class="bg-white text-alpha-700" value="DJI">Djibouti</option>
                            <option class="bg-white text-alpha-700" value="DMA">Dominica</option>
                            <option class="bg-white text-alpha-700" value="DOM">Dominican Republic</option>
                            <option class="bg-white text-alpha-700" value="ECU">Ecuador</option>
                            <option class="bg-white text-alpha-700" value="EGY">Egypt</option>
                            <option class="bg-white text-alpha-700" value="SLV">El Salvador</option>
                            <option class="bg-white text-alpha-700" value="GNQ">Equatorial Guinea</option>
                            <option class="bg-white text-alpha-700" value="EST">Estonia</option>
                            <option class="bg-white text-alpha-700" value="ETH">Ethiopia</option>
                            <option class="bg-white text-alpha-700" value="FLK">Falkland Islands (Malvinas)</option>
                            <option class="bg-white text-alpha-700" value="FRO">Faroe Islands</option>
                            <option class="bg-white text-alpha-700" value="FJI">Fiji</option>
                            <option class="bg-white text-alpha-700" value="FIN">Finland</option>
                            <option class="bg-white text-alpha-700" value="FRA">France</option>
                            <option class="bg-white text-alpha-700" value="FXX">France, Metropolitan</option>
                            <option class="bg-white text-alpha-700" value="GUF">French Guiana</option>
                            <option class="bg-white text-alpha-700" value="ATF">French Southern Territories</option>
                            <option class="bg-white text-alpha-700" value="GAB">Gabon</option>
                            <option class="bg-white text-alpha-700" value="GEO">Georgia</option>
                            <option class="bg-white text-alpha-700" value="DEU">Germany</option>
                            <option class="bg-white text-alpha-700" value="GHA">Ghana</option>
                            <option class="bg-white text-alpha-700" value="GIB">Gibraltar</option>
                            <option class="bg-white text-alpha-700" value="GGY">Guernsey</option>
                            <option class="bg-white text-alpha-700" value="GRC">Greece</option>
                            <option class="bg-white text-alpha-700" value="GRL">Greenland</option>
                            <option class="bg-white text-alpha-700" value="GRD">Grenada</option>
                            <option class="bg-white text-alpha-700" value="GLP">Guadeloupe</option>
                            <option class="bg-white text-alpha-700" value="GTM">Guatemala</option>
                            <option class="bg-white text-alpha-700" value="GIN">Guinea</option>
                            <option class="bg-white text-alpha-700" value="GUY">Guyana</option>
                            <option class="bg-white text-alpha-700" value="HMD">Heard and Mc Donald Islands</option>
                            <option class="bg-white text-alpha-700" value="HND">Honduras</option>
                            <option class="bg-white text-alpha-700" value="HKG">Hong Kong</option>
                            <option class="bg-white text-alpha-700" value="HUN">Hungary</option>
                            <option class="bg-white text-alpha-700" value="ISL">Iceland</option>
                            <option class="bg-white text-alpha-700" value="IND">India</option>
                            <option class="bg-white text-alpha-700" value="IMN">Isle of Man</option>
                            <option class="bg-white text-alpha-700" value="IDN">Indonesia</option>
                            <option class="bg-white text-alpha-700" value="IRL">Ireland</option>
                            <option class="bg-white text-alpha-700" value="ISR">Israel</option>
                            <option class="bg-white text-alpha-700" value="ITA">Italy</option>
                            <option class="bg-white text-alpha-700" value="JEY">Jersey</option>
                            <option class="bg-white text-alpha-700" value="JAM">Jamaica</option>
                            <option class="bg-white text-alpha-700" value="JOR">Jordan</option>
                            <option class="bg-white text-alpha-700" value="KAZ">Kazakhstan</option>
                            <option class="bg-white text-alpha-700" value="KEN">Kenya</option>
                            <option class="bg-white text-alpha-700" value="KOR">Korea, Republic of</option>
                            <option class="bg-white text-alpha-700" value="XKX">Kosovo</option>
                            <option class="bg-white text-alpha-700" value="KWT">Kuwait</option>
                            <option class="bg-white text-alpha-700" value="KGZ">Kyrgyzstan</option>
                            <option class="bg-white text-alpha-700" value="LVA">Latvia</option>
                            <option class="bg-white text-alpha-700" value="LSO">Lesotho</option>
                            <option class="bg-white text-alpha-700" value="LIE">Liechtenstein</option>
                            <option class="bg-white text-alpha-700" value="LTU">Lithuania</option>
                            <option class="bg-white text-alpha-700" value="LUX">Luxembourg</option>
                            <option class="bg-white text-alpha-700" value="MAC">Macau</option>
                            <option class="bg-white text-alpha-700" value="MKD">Macedonia</option>
                            <option class="bg-white text-alpha-700" value="MDG">Madagascar</option>
                            <option class="bg-white text-alpha-700" value="MWI">Malawi</option>
                            <option class="bg-white text-alpha-700" value="MYS">Malaysia</option>
                            <option class="bg-white text-alpha-700" value="MDV">Maldives</option>
                            <option class="bg-white text-alpha-700" value="MLT">Malta</option>
                            <option class="bg-white text-alpha-700" value="MTQ">Martinique</option>
                            <option class="bg-white text-alpha-700" value="MRT">Mauritania</option>
                            <option class="bg-white text-alpha-700" value="MUS">Mauritius</option>
                            <option class="bg-white text-alpha-700" value="MYT">Mayotte</option>
                            <option class="bg-white text-alpha-700" value="MEX">Mexico</option>
                            <option class="bg-white text-alpha-700" value="MDA">Moldova, Republic of</option>
                            <option class="bg-white text-alpha-700" value="MCO">Monaco</option>
                            <option class="bg-white text-alpha-700" value="MNG">Mongolia</option>
                            <option class="bg-white text-alpha-700" value="MNE">Montenegro</option>
                            <option class="bg-white text-alpha-700" value="MSR">Montserrat</option>
                            <option class="bg-white text-alpha-700" value="MAR">Morocco</option>
                            <option class="bg-white text-alpha-700" value="NRU">Nauru</option>
                            <option class="bg-white text-alpha-700" value="NPL">Nepal</option>
                            <option class="bg-white text-alpha-700" value="NCL">New Caledonia</option>
                            <option class="bg-white text-alpha-700" value="NZL">New Zealand</option>
                            <option class="bg-white text-alpha-700" value="NIC">Nicaragua</option>
                            <option class="bg-white text-alpha-700" value="NIU">Niue</option>
                            <option class="bg-white text-alpha-700" value="NFK">Norfolk Island</option>
                            <option class="bg-white text-alpha-700" value="NOR">Norway</option>
                            <option class="bg-white text-alpha-700" value="OMN">Oman</option>
                            <option class="bg-white text-alpha-700" value="PAK">Pakistan</option>
                            <option class="bg-white text-alpha-700" value="PLW">Palau</option>
                            <option class="bg-white text-alpha-700" value="PAN">Panama</option>
                            <option class="bg-white text-alpha-700" value="PRY">Paraguay</option>
                            <option class="bg-white text-alpha-700" value="PER">Peru</option>
                            <option class="bg-white text-alpha-700" value="PHL">Philippines</option>
                            <option class="bg-white text-alpha-700" value="PCN">Pitcairn</option>
                            <option class="bg-white text-alpha-700" value="POL">Poland</option>
                            <option class="bg-white text-alpha-700" value="PRT">Portugal</option>
                            <option class="bg-white text-alpha-700" value="QAT">Qatar</option>
                            <option class="bg-white text-alpha-700" value="REU">Reunion</option>
                            <option class="bg-white text-alpha-700" value="ROU">Romania</option>
                            <option class="bg-white text-alpha-700" value="RWA">Rwanda</option>
                            <option class="bg-white text-alpha-700" value="KNA">Saint Kitts and Nevis</option>
                            <option class="bg-white text-alpha-700" value="LCA">Saint Lucia</option>
                            <option class="bg-white text-alpha-700" value="VCT">Saint Vincent and the Grenadines</option>
                            <option class="bg-white text-alpha-700" value="WSM">Samoa</option>
                            <option class="bg-white text-alpha-700" value="SMR">San Marino</option>
                            <option class="bg-white text-alpha-700" value="STP">Sao Tome and Principe</option>
                            <option class="bg-white text-alpha-700" value="SAU">Saudi Arabia</option>
                            <option class="bg-white text-alpha-700" value="SEN">Senegal</option>
                            <option class="bg-white text-alpha-700" value="SRB">Serbia</option>
                            <option class="bg-white text-alpha-700" value="SYC">Seychelles</option>
                            <option class="bg-white text-alpha-700" value="SLE">Sierra Leone</option>
                            <option class="bg-white text-alpha-700" value="SGP">Singapore</option>
                            <option class="bg-white text-alpha-700" value="SVK">Slovakia</option>
                            <option class="bg-white text-alpha-700" value="SVN">Slovenia</option>
                            <option class="bg-white text-alpha-700" value="ZAF">South Africa</option>
                            <option class="bg-white text-alpha-700" value="SGS">South Georgia South Sandwich Islands</option>
                            <option class="bg-white text-alpha-700" value="ESP">Spain</option>
                            <option class="bg-white text-alpha-700" value="LKA">Sri Lanka</option>
                            <option class="bg-white text-alpha-700" value="SHN">St. Helena</option>
                            <option class="bg-white text-alpha-700" value="SPM">St. Pierre and Miquelon</option>
                            <option class="bg-white text-alpha-700" value="SUR">Suriname</option>
                            <option class="bg-white text-alpha-700" value="SJM">Svalbard and Jan Mayen Islands</option>
                            <option class="bg-white text-alpha-700" value="SWZ">Swaziland</option>
                            <option class="bg-white text-alpha-700" value="SWE">Sweden</option>
                            <option class="bg-white text-alpha-700" value="CHE">Switzerland</option>
                            <option class="bg-white text-alpha-700" value="TWN">Taiwan</option>
                            <option class="bg-white text-alpha-700" value="TZA">Tanzania, United Republic of</option>
                            <option class="bg-white text-alpha-700" value="THA">Thailand</option>
                            <option class="bg-white text-alpha-700" value="TGO">Togo</option>
                            <option class="bg-white text-alpha-700" value="TKL">Tokelau</option>
                            <option class="bg-white text-alpha-700" value="TON">Tonga</option>
                            <option class="bg-white text-alpha-700" value="TTO">Trinidad and Tobago</option>
                            <option class="bg-white text-alpha-700" value="TUN">Tunisia</option>
                            <option class="bg-white text-alpha-700" value="TUR">Turkey</option>
                            <option class="bg-white text-alpha-700" value="TKM">Turkmenistan</option>
                            <option class="bg-white text-alpha-700" value="TCA">Turks and Caicos Islands</option>
                            <option class="bg-white text-alpha-700" value="UGA">Uganda</option>
                            <option class="bg-white text-alpha-700" value="UKR">Ukraine</option>
                            <option class="bg-white text-alpha-700" value="ARE">United Arab Emirates</option>
                            <option class="bg-white text-alpha-700" value="GBR">United Kingdom</option>
                            <option class="bg-white text-alpha-700" value="URY">Uruguay</option>
                            <option class="bg-white text-alpha-700" value="UZB">Uzbekistan</option>
                            <option class="bg-white text-alpha-700" value="VUT">Vanuatu</option>
                            <option class="bg-white text-alpha-700" value="VAT">Vatican City State</option>
                            <option class="bg-white text-alpha-700" value="VNM">Vietnam</option>
                            <option class="bg-white text-alpha-700" value="VGB">Virgin Islands (British)</option>
                            <option class="bg-white text-alpha-700" value="WLF">Wallis and Futuna Islands</option>
                            <option class="bg-white text-alpha-700" value="ZMB">Zambia</option>
                            <option class="bg-white text-alpha-700" value="CUW">countries.cw</option>
                        </select>
                    </div>
                </div>
                <div style="justify-self: center;">
                    <button type="submit" class="btn btn-success sm-rounded">Create Link</button>
                </div>
            </form>
        </div>
        @if (isset($checkout_id))
        <div class="container m-3 text-center">
            <div class="row align-iems-center text-center justify-content-center">
                <strong class="text-center">Generated Payment Link:
                    <small><a href=" http://127.0.0.1:8000/p3/payment-link/{{ $checkout_id }}" id="link"> http://127.0.0.1:8000/p3/payment-link/{{ $checkout_id }}</a></small>
                </strong>
                <div class="px-2 text-center">
                    <button class=" btn fa-solid fa-copy" onclick="copytext()"></button>
                </div>
            </div>
        </div>
        @endif

    </div>
    <!-- Optional JavaScript -->
    <script>
        function copytext() {
            console.log('function called');
            var copyText = document.getElementById("link");
            var textToCopy = copyText.innerHTML;
            navigator.clipboard.writeText(textToCopy);
        }
    </script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
