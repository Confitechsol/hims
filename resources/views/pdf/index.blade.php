<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$IpdPatient->patient['patient_name']}}</title>
  </head>
  <style>

    body {
      font-family: 'Trebuchet MS', sans-serif;
    }

    .first_logo, .second_logo {
      font-size: 12px;
    }

    .about_info {
      font-size: 12px;
    }

    .wreeti {
      font-size: 10px;
    }

    .main_box {
      padding: 20px;
      max-width: 1199px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      margin: 20px auto;
      width: 210mm;
      height: 297mm;
      line-height: 10px;
    }

    .top_head {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .about_info {
      text-align: end;
      line-height: 0.5;
    }

    .heading h4 {
      text-transform: uppercase;
      text-align: center;
      margin: 10px auto;
    }

    .red {
      color: #ff3405;
      font-weight: 700;
    }

    .admission_info {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      /* padding: 0 12px; */
      font-size: 10px;
    }

    .patient_info {
      border: 2px solid #282828;
      display: flex;
      padding: 8px;
      margin-bottom: 2px;
      font-weight: 700;
      font-size: 10px;
    }

    .patient_details {
      width: 50%;
    }

    .patient_items {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 10px;
    }

    .patient_head {
      width: 80px;
    }

    .patient_box {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .general_list li {
      margin-bottom: 10px;
    }

    .bottom_box {
      border-bottom: 2px dashed #282828;
      display: flex;
      align-items: end;
      justify-content: space-between;
      font-size: 10px;
    }

    .contact_box {
      width: 80%;
    }

    .sig_box {
      border-top: 2px solid #282828;
      width: 20%;
    }

    .blue {
      color: #010080;
      font-weight: 700;
    }

    .wreeti_items {
      font-weight: 700;
      margin-bottom: 8px ;
    }

    .wreeti_box {
      display: flex;
      align-items: center;
      gap: 50px;
    }

    .wreeti_sig {
      display: flex;
      align-items: end;
      gap: 10px;
      font-size: 10px;
      margin-top: 30px;
    }

    .wreeti_sig p {
      margin-bottom: 0;
    }

    .line {
      border: 1px solid #282828;
      width: 40%;
    }

    .text {
      font-size: 10px;
    }

  </style>
  <body>

    <div class="main_box" id="pdf-content">

      <div class="top_head">
        <div class="first_logo">
          <img src="{{asset('assets/images/logo.webp')}}" alt="LOGO1" style="height: 50px">
          <p>NABH/PESHCO-2018-3150/L-03
          </p>
        </div>
        <div class="second_logo">
          {{-- <img src alt="LOGO2"> --}}
        </div>
        <div class="about_info">
          <p>Samaritan Clinic Pvt.Ltd. </p>
          <p>10/4D, ELGIN ROAD , KOLKATA - 700 020</p>
          <p>Phone - 033 4060 8313</p>
          <p> 033 4029 2156</p>
          <p>Ambulance :- 96747 77261</p>
          <p>E-mail: samaritan84@gmail.com</p>
        </div>
      </div>

      <div class="heading">
        <h4 class="red"> ADMISSION FORM
        </h4>
      </div>

      <div class="admission_info">
        <div class="admission_item">
          <p>
            <b>ADMISSION NO.</b> : <span class="red">{{$IpdPatient->ipd['ipd_no']}}</span>
          </p>
        </div>
        <div class="admission_item">
          <p>
            <b>ADMISSION DATE.</b> : {{\Carbon\Carbon::parse($IpdPatient->created_at)->format('d-m-Y') ?? ''}}
          </p>
        </div>
        <div class="admission_item">
          <p>
            <b>ADMISSION TIME</b> : <span class="red">{{\Carbon\Carbon::parse($IpdPatient->created_at)->format('h:i') ?? ''}} {{\Carbon\Carbon::parse($IpdPatient->created_at)->format('A') ?? ''}}</span>
          </p>
        </div>
      </div>

      <div class="patient_info">

        <div class="patient_details">

          <div class="patient_items">
            <div class="patient_head">
              PATIENT NAME
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['patient_name']}}
            </div>
          </div>

          <div class="patient_items">
            <div class="patient_head">
              ADDRESS
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['address']}}
            </div>
          </div>

          <div class="patient_items">
            <div class="patient_head">
              RELIGION
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['religion'] ?? ""}}
            </div>
          </div>
          <div class="patient_items">
            <div class="patient_head">
              URN
            </div>
            :
            <div class="patient_data">
              .
            </div>
          </div>
          <div class="patient_items">
            <div class="patient_head">
              OCCUPATION
            </div>
            :
            <div class="patient_data">
              .
            </div>
          </div>

        </div>

        <div class="patient_details">
          <div class="patient_box">
            <div class="patient_items">
              <div class="patient_head">
                AGE
              </div>
              :
              <div class="patient_data">
                {{$IpdPatient->patient['age']}} y
              </div>
            </div>
            <div class="patient_items">
              <div class="patient_head">
                GENDER
              </div>
              :
              <div class="patient_data">
                {{$IpdPatient->patient['gender']}}
              </div>
            </div>
          </div>
          <div class="patient_items">
            <div class="patient_head">
              AREA
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['address']}}
            </div>
          </div>
          <div class="patient_items">
            <div class="patient_head">
              STATUS
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['marital_status']}}
            </div>
          </div>
          <div class="patient_items">
            <div class="patient_head">
              PHONE NO.
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['mobileno'] ?? ""}}
            </div>
          </div>
          <div class="patient_items">
            <div class="patient_head">
              NATIONALITY
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['nationality'] ?? ""}}
            </div>
          </div>
        </div>

      </div>

      <div class="patient_info">

        <div class="patient_details">

          <div class="patient_items">
            <div class="patient_head">
              W/O S/O D/O
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['guardian_name']}}
            </div>
          </div>

          <div class="patient_items">
            <div class="patient_head">
              RELATIVE NAME
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['guardian_name']}}
            </div>
          </div>

        </div>

        <div class="patient_details">

          <div class="patient_items">
            <div class="patient_head">
              RELATION
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['guardian_relation'] ?? ""}}
            </div>
          </div>

          <div class="patient_items">
            <div class="patient_head">
              PHONE NO.
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->patient['guardian_phone'] ?? ""}}
            </div>
          </div>

        </div>

      </div>

      <div class="patient_info">

        <div class="patient_details">

          <div class="patient_box">

            <div class="patient_items">
              <div class="patient_head">
                BED NO.
              </div>
              :
              <div class="patient_data">
                {{$IpdPatient->ipd['bedDetail']['name'] ?? ''}}
              </div>
            </div>

            <div class="patient_items">
              <div class="patient_head">
                BED Rate.
              </div>
              :
              <div class="patient_data">
                {{$IpdPatient->ipd['bedDetail']['bedGroup']['bed_cost'] ?? ''}}
              </div>
            </div>

          </div>

          <div class="patient_items">
            <div class="patient_head">
              UNDER CARE
            </div>
            :
            <div class="patient_data">
              Dr. {{$IpdPatient->doctor['name'] }} {{$IpdPatient->doctor['surname']}} ({{$IpdPatient->doctor['registration_no']}})
            </div>
          </div>

        </div>

        <div class="patient_details">

          <div class="patient_items">
            <div class="patient_head">
              DEPARTMENT
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->ipd['bedDetail']['bedGroup']['name'] ?? ''}}
            </div>
          </div>

        </div>

      </div>

      <div class="patient_info">

        <div class="patient_details">

          <div class="patient_items">
            <div class="patient_head">
              TPA
            </div>
            :
            <div class="patient_data">
              {{$IpdPatient->ipd->organisation['organisation_name'] ?? ""}}
            </div>
          </div>

        </div>

        <div class="patient_details">

          <div class="patient_items">
            <div class="patient_head">
              COMPANY
            </div>
            :
            <div class="patient_data">
              United India Insurance Company Ltd.
            </div>
          </div>

        </div>

      </div>

      <div class="text">
        <p> I do hereby give my full consent to undertake treatment of above
          Patient by Medical Management, Surgical Management, Instensive Care at
          this Nursing Home.</p>

        <p>I..............................................................................
          in my full sense hereby authorize Dr.
          .................................................................................
          & Such Associates, Doctors, Consultants, Nurses & Paramedical staff of
          Hospital to conduct all necessary Investigation, Medical / Surgical /
          Procedure on me/my patient under General or religional anaesthesia as
          deemed suitable for the same.</p>

        <p> I agree to pay all the bills and when submitted by hospital
          authority for my/my patient's treatment , and clear all the dues of
          Nursing Home
          incurred for the treatment of the patient before discharge /DORB.</p>

        <p> I shall not hold the institution, it's staff and the doctors
          responsible for any unwanted consequences during the course of medical
          treatment
          and the surgery administration of anaesthesia/drug or
          investigation/treatment etc..</p>

        <p>I have been fully explained the consequences of the procedures and
          their risks.</p>

        <h4>GENERAL NORMS FOR PATIENT ADMISSION:</h4>

        <ol class="general_list">
          <li>An ADVANCE PAYMENT shuld be made at the time of admission
            accordingly
            <br><br>
            <span>a) Rs. 5000/- For GENERAL WARD</span>
            <span>b) Rs. 8000/- For CABINS.</span>
            <span>c) Rs. 15000/- For ICU.</span>
          </li>
          <li>A minimum of 80% to 85% amount of the surgery package must be paid
            before the oparation .</li>
          <li>Patient should NOT bear any cash, valuables, mobile phone etc.
            during his/her stay in the Nursing Home.</li>
          <li>Only two persons are allowed during visiting hours, childrens are
            allowed only on Sunday evening.</li>
          <li>No foods from outside are allowed without prior permission.</li>
          <li>Patient availing cash less facility should submit His/Her
            documents at the Insurance Desk.</li>
          <li>Patient Party should enquire about there outstanding payment
            regularly from the respective counters, so that maximum outstanding
            does
            not exceeds Rs.10,000</li>
          <li>Shifting from ICU to Ward depends on bed availiabilty.</li>
          <li>PATIENT / PARTIES ID DOCUMENT IS MANDATARY . PLEASE PROVIDE US AT
            THE EARLIEST.</li>
          <li>Whether any reimbusement for claim will be availed against any
            insurance policy or health scheme in connection with the treatment
            of the
            patient. Yes [ ] No. [ ] If you don t disclose in the consent form
            by ticking Yes/No, then Samaritan Clinic Pvt. Ltd. will not be
            liable for any reimbursement insu</li>
        </ol>
        <p>Witness Signature with relation</p>
      </div>

      <div class="bottom_box">
        <div class="contact_box">
          <p>Contact No :</p>
          <p class="red">Full charge on the day of the admission. No charge if
            the patient leaves before 11:30 am on the day of discharge.</p>
        </div>
        <div class="sig_box">
          <p>Signature of Patient / Party</p>
        </div>
      </div>

      <div class="heading">
        <h4 class="blue"> FOR OFFICE USE
        </h4>
      </div>

      <div class="admission_info">
        <div class="admission_item">
          <p>
            <b>Date of Admission </b>: {{\Carbon\Carbon::parse($IpdPatient->created_at)->format('d-m-Y') ?? ''}}
          </p>
        </div>
        <div class="admission_item">
          <p>
            <b>ADMISSION TIME.</b> : {{\Carbon\Carbon::parse($IpdPatient->created_at)->format('h:i') ?? ''}} {{\Carbon\Carbon::parse($IpdPatient->created_at)->format('A') ?? ''}}
          </p>
        </div>
        <div class="admission_item">
          <p>
            <b>Admission No</b> : <span class="red">{{$IpdPatient->ipd['ipd_no']}}</span>
          </p>
        </div>
        <div class="admission_item">
          <p>
            <b>BED No</b> : {{$IpdPatient->ipd['bedDetail']['name'] ?? ''}}
          </p>
        </div>
        <div class="admission_item">
          <p>
            <b>Under Care Docto</b> : Dr. ABHIMANYU BASU
            (REG45425)
          </p>
        </div>
      </div>

      <div class="wreeti">
        <div class="wreeti_box">
          <div class="wreeti_items">
            <span class="red">VEGETARIAN : </span>
            <span><input type="checkbox" name id></span>
            <label for class="red">Yes</label>
            <span><input type="checkbox" name id></span>
            <label for class="red">NO</label>
          </div>
          <div class="wreeti_items">
            <span class="red">Insurance / TPA : </span>

          </div>
        </div>
        <div class="wreeti_items">
          <span class="red">Patient's History : </span>
          <span><input type="checkbox" name id></span>
          <label for class="red">Diabetic</label>
          <span><input type="checkbox" name id></span>
          <label for class="red">HTN</label>
          <span><input type="checkbox" name id></span>
          <label for class="red">Asthma</label>
          <span><input type="checkbox" name id></span>
          <label for class="red">Cardiac</label>
        </div>
        <div class="wreeti_items">
          <span class="red">Allergies to Food and/or Drugs : </span>
          <span><input type="checkbox" name id></span>
          <label for class="red">Asprin / Ecosprin</label>
          <span><input type="checkbox" name id></span>
          <label for class="red">Clopitogril</label>
          <span><input type="checkbox" name id></span>
          <label for class="red">Others</label>
        </div>
      </div>

      <div class="wreeti_sig">
        <p><b>Signature of the Front Office Executive : </b>WREETI</p>
        <div class="line">

        </div>

        <p><b>DATE : </b></p>
        <p><b>{{\Carbon\Carbon::parse($IpdPatient->created_at)->format('d-m-Y') ?? ''}}</b></p>
      </div>
    </div>
    </body>
  </html>