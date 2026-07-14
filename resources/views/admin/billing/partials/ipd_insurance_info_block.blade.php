{{-- Insurance block on approval bill — after patient info header --}}
<div class="patient_info" style="margin-top: 8px;">
    <table>
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                <table style="width: 100%;">
                    <tr>
                        <td class="patient_label">Company Name</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ strtoupper($ipd->insuranceCompany->name ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="patient_label">TPA Name</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ strtoupper($ipd->organisation->organisation_name ?? 'N/A') }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                <table style="width: 100%;">
                    <tr>
                        <td class="patient_label">Policy No.</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->insurance_policy_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="patient_label">Card No.</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->insurance_card_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="patient_label">CCN No.</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->ccn_no ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
