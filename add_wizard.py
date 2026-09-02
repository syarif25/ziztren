import re

filepath = 'resources/views/beasiswa/create.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# 1. Add CSS for Wizard
css_wizard = '''
    <style>
        .wizard-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            padding: 0 40px;
        }
        .wizard-progress::before {
            content: '';
            position: absolute;
            top: 25px; /* center of the circle */
            left: 40px;
            right: 40px;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .wizard-step {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        .wizard-step .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            margin: 0 auto 10px auto;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #e9ecef;
            transition: all 0.3s ease;
        }
        .wizard-step .step-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        
        /* Completed State */
        .wizard-step.completed .step-circle {
            background-color: #28a745;
            color: white;
            box-shadow: 0 0 0 3px #28a745;
        }
        .wizard-step.completed .step-label {
            color: #28a745;
        }
        
        /* Active line */
        .wizard-step.completed + .wizard-step::before {
             /* We'll handle line color dynamically via JS or just keep it simple */
        }
    </style>
'''

content = content.replace('</head>', css_wizard + '\n</head>')


# 2. Add HTML Wizard structure above tabs
html_wizard = '''
                                        <!-- Wizard Progress Bar -->
                                        <div class="wizard-progress">
                                            <div class="wizard-step" id="wizard-step-1">
                                                <div class="step-circle"><i class="ri-user-line"></i></div>
                                                <div class="step-label">Data Pemohon</div>
                                            </div>
                                            <div class="wizard-step completed" id="wizard-step-2">
                                                <div class="step-circle"><i class="ri-file-text-line"></i></div>
                                                <div class="step-label">Dokumen</div>
                                            </div>
                                            <div class="wizard-step" id="wizard-step-3">
                                                <div class="step-circle"><i class="ri-money-dollar-circle-line"></i></div>
                                                <div class="step-label">Jumlah Bantuan</div>
                                            </div>
                                        </div>
                                        <hr class="mb-4">
'''

# Find where the nav-tabs start and insert before it
content = content.replace('<!-- Nav tabs -->', html_wizard + '\n                                        <!-- Nav tabs -->')


# 3. Update Javascript to toggle the wizard classes
js_target = r"if \(tab1_complete\) \{\s*\$\('#ind-tab1'\)\.removeClass\('bg-danger'\)\.addClass\('bg-success'\)\.text\('✅'\);\s*\} else \{\s*\$\('#ind-tab1'\)\.removeClass\('bg-success'\)\.addClass\('bg-danger'\)\.text\('❌'\);\s*\}"

js_replace_1 = '''if (tab1_complete) {
                        $('#ind-tab1').removeClass('bg-danger').addClass('bg-success').text('✅');
                        $('#wizard-step-1').addClass('completed');
                        $('#wizard-step-1 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#ind-tab1').removeClass('bg-success').addClass('bg-danger').text('❌');
                        $('#wizard-step-1').removeClass('completed');
                        $('#wizard-step-1 .step-circle').html('<i class="ri-user-line"></i>');
                    }'''

content = re.sub(js_target, js_replace_1, content)


js_target_2 = r"if \(tab3_complete\) \{\s*\$\('#ind-tab3'\)\.removeClass\('bg-danger'\)\.addClass\('bg-success'\)\.text\('✅'\);\s*\} else \{\s*\$\('#ind-tab3'\)\.removeClass\('bg-success'\)\.addClass\('bg-danger'\)\.text\('❌'\);\s*\}"

js_replace_2 = '''if (tab3_complete) {
                        $('#ind-tab3').removeClass('bg-danger').addClass('bg-success').text('✅');
                        $('#wizard-step-3').addClass('completed');
                        $('#wizard-step-3 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#ind-tab3').removeClass('bg-success').addClass('bg-danger').text('❌');
                        $('#wizard-step-3').removeClass('completed');
                        $('#wizard-step-3 .step-circle').html('<i class="ri-money-dollar-circle-line"></i>');
                    }
                    
                    // Update Tab 2 (always complete for now)
                    $('#wizard-step-2 .step-circle').html('<i class="ri-check-line"></i>');
                    '''

content = re.sub(js_target_2, js_replace_2, content)


with open(filepath, 'w') as f:
    f.write(content)

print("Added Form Wizard UI")
