<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Check if response ID is provided
if (!isset($_GET['response_id']) && !isset($_GET['narrative_id'])) {
    die('Response ID or Narrative ID is required');
}

// Read CSV data
$csvFile = 'responses.csv';
if (!file_exists($csvFile)) {
    die('No responses file found');
}

$allData = [];
$headers = [];
if (($handle = fopen($csvFile, 'r')) !== false) {
    $row = 0;
    while (($data = fgetcsv($handle)) !== false) {
        if ($row === 0) {
            $headers = $data;
        } else {
            $allData[] = array_combine($headers, $data);
        }
        $row++;
    }
    fclose($handle);
}

if (isset($_GET['response_id'])) {
    $responseId = intval($_GET['response_id']);
    if ($responseId < 1 || $responseId > count($allData)) {
        die('Invalid response ID');
    }
    
    $resp = $allData[$responseId - 1];
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Dreams & Culture Admin Dashboard');
    $pdf->SetAuthor('Dreams & Culture Research');
    $pdf->SetTitle('Response #' . $responseId . ' - Dreams & Culture Questionnaire');
    $pdf->SetSubject('Cultural Dream Research Response');
    
    // Set default header data
    $pdf->SetHeaderData('', 0, 'Dreams & Culture Questionnaire', 'Response #' . $responseId . ' - ' . date('M j, Y'));
    
    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', '', 12);
    
    // Generate intelligent summary
    $summary_parts = [];
    if (!empty($resp['age']) && !empty($resp['gender']) && !empty($resp['location'])) {
        $summary_parts[] = "A " . $resp['age'] . "-year-old " . $resp['gender'] . " from " . $resp['location'];
    }
    if (!empty($resp['tribe'])) {
        $summary_parts[] = "of " . $resp['tribe'] . " cultural background";
    }
    if (!empty($resp['dream_frequency'])) {
        $freq_text = strtolower($resp['dream_frequency']);
        if ($freq_text == 'every_night' || $freq_text == 'every night') {
            $summary_parts[] = "who remembers dreams every night";
        } elseif ($freq_text == 'few_times_week' || $freq_text == 'a few times a week') {
            $summary_parts[] = "who remembers dreams a few times a week";
        } elseif ($freq_text == 'occasionally') {
            $summary_parts[] = "who occasionally remembers dreams";
        } elseif ($freq_text == 'rarely') {
            $summary_parts[] = "who rarely remembers dreams";
        } elseif ($freq_text == 'never') {
            $summary_parts[] = "who never remembers dreams";
        }
    }
    
    if (!empty($summary_parts)) {
        $pdf->SetFont('helvetica', 'I', 14);
        $pdf->Cell(0, 10, 'Summary: ' . implode(', ', $summary_parts) . '.', 0, 1, 'L');
        $pdf->Ln(5);
    }
    
    // Content sections
    $sections = [
        ['title' => 'Personal Information', 'icon' => '👤', 'fields' => ['age', 'gender', 'tribe', 'languages', 'location']],
        ['title' => 'Dream Habits', 'icon' => '🌙', 'fields' => ['dream_frequency', 'tell_someone', 'tell_whom', 'record_dreams']],
        ['title' => 'Cultural Beliefs About Dreams', 'icon' => '🏛️', 'fields' => ['special_meanings', 'explanation', 'stories', 'go_to']],
        ['title' => 'Personal Dream Experiences', 'icon' => '💭', 'fields' => ['dream_description', 'dream_feeling', 'attach_meaning', 'meaning_description']],
        ['title' => 'Dream Symbols and Meanings', 'icon' => '🔮', 'fields' => ['symbols', 'symbol_meanings', 'omens_examples']],
        ['title' => 'Impact of Dreams', 'icon' => '⚡', 'fields' => ['influenced', 'influence_description', 'rituals', 'ritual_actions']],
        ['title' => 'Sharing and Consent', 'icon' => '🤝', 'fields' => ['share_with_ai', 'additional_comments', 'consent']]
    ];
    
    foreach ($sections as $section) {
        $hasContent = false;
        foreach ($section['fields'] as $field) {
            if (!empty($resp[$field])) {
                $hasContent = true;
                break;
            }
        }
        
        if ($hasContent) {
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor(0, 123, 255);
            $pdf->Cell(0, 10, $section['icon'] . ' ' . $section['title'], 0, 1, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 11);
            
            foreach ($section['fields'] as $field) {
                if (!empty($resp[$field])) {
                    $fieldName = ucwords(str_replace('_', ' ', $field));
                    $value = $resp[$field];
                    
                    // Special formatting for certain fields
                    if ($field == 'tell_someone' || $field == 'record_dreams' || $field == 'rituals') {
                        $value = strtolower($value) == 'yes' ? 'Yes' : 'No';
                    }
                    
                    $pdf->SetFont('helvetica', 'B', 11);
                    $pdf->Cell(0, 8, $fieldName . ':', 0, 1, 'L');
                    $pdf->SetFont('helvetica', '', 11);
                    
                    // Handle long text fields
                    if (strlen($value) > 100) {
                        $pdf->MultiCell(0, 6, $value, 0, 'L');
                    } else {
                        $pdf->Cell(0, 6, $value, 0, 1, 'L');
                    }
                    $pdf->Ln(2);
                }
            }
            $pdf->Ln(5);
        }
    }
    
    // Output PDF
    $filename = 'Response_' . $responseId . '_Dreams_Culture.pdf';
    $pdf->Output($filename, 'D');
    
} elseif (isset($_GET['narrative_id'])) {
    $narrativeId = intval($_GET['narrative_id']);
    if ($narrativeId < 1 || $narrativeId > count($allData)) {
        die('Invalid narrative ID');
    }
    
    $resp = $allData[$narrativeId - 1];
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Dreams & Culture Admin Dashboard');
    $pdf->SetAuthor('Dreams & Culture Research');
    $pdf->SetTitle('Narrative #' . $narrativeId . ' - Cultural Dream Story');
    $pdf->SetSubject('Cultural Dream Research Narrative');
    
    // Set default header data
    $pdf->SetHeaderData('', 0, 'Cultural Dream Narrative', 'Response #' . $narrativeId . ' - ' . date('M j, Y'));
    
    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(0, 123, 255);
    $pdf->Cell(0, 15, 'Cultural Dream Narrative', 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(5);
    
    // Generate comprehensive narrative paragraph
    $narrative = [];
    
    // Personal introduction
    $intro = "I am";
    if (!empty($resp['age'])) {
        $intro .= " a " . $resp['age'] . "-year-old";
    }
    if (!empty($resp['gender'])) {
        $intro .= " " . $resp['gender'];
    }
    if (!empty($resp['tribe'])) {
        $intro .= " from " . $resp['tribe'] . " culture";
    }
    if (!empty($resp['languages'])) {
        $intro .= ". I speak " . $resp['languages'];
    }
    if (!empty($resp['location'])) {
        $intro .= " and currently live in " . $resp['location'];
    }
    $intro .= ".";
    $narrative[] = $intro;
    
    // Dream habits narrative
    if (!empty($resp['dream_frequency'])) {
        $freq_text = strtolower($resp['dream_frequency']);
        if ($freq_text == 'every_night' || $freq_text == 'every night') {
            $dream_habit = "When it comes to my dreams, I remember them every single night.";
        } elseif ($freq_text == 'few_times_week' || $freq_text == 'a few times a week') {
            $dream_habit = "I remember my dreams a few times each week.";
        } elseif ($freq_text == 'occasionally') {
            $dream_habit = "I occasionally remember my dreams.";
        } elseif ($freq_text == 'rarely') {
            $dream_habit = "I rarely remember my dreams.";
        } elseif ($freq_text == 'never') {
            $dream_habit = "I never remember my dreams.";
        } else {
            $dream_habit = "Regarding dream frequency, " . $resp['dream_frequency'] . ".";
        }
        
        if (!empty($resp['tell_someone'])) {
            if (strtolower($resp['tell_someone']) == 'yes') {
                $dream_habit .= " When I do remember them, I usually share them with others";
                if (!empty($resp['tell_whom'])) {
                    $dream_habit .= ", particularly with " . $resp['tell_whom'];
                }
                $dream_habit .= ".";
            } else {
                $dream_habit .= " When I do remember them, I typically keep them to myself.";
            }
        }
        
        if (!empty($resp['record_dreams'])) {
            if (strtolower($resp['record_dreams']) == 'yes') {
                $dream_habit .= " I also make it a practice to write down or record my dreams.";
            } else {
                $dream_habit .= " I don't usually write down or record my dreams.";
            }
        }
        
        $narrative[] = $dream_habit;
    }
    
    // Cultural beliefs narrative
    if (!empty($resp['special_meanings']) || !empty($resp['explanation'])) {
        $cultural_belief = "";
        if (!empty($resp['special_meanings'])) {
            $meaning_response = strtolower($resp['special_meanings']);
            if ($meaning_response == 'yes') {
                $cultural_belief = "In my culture and family, we strongly believe that dreams carry special meanings and significance.";
            } elseif ($meaning_response == 'no') {
                $cultural_belief = "In my culture and family, dreams are not typically viewed as having special meanings.";
            } else {
                $cultural_belief = "Regarding whether dreams have special meanings in my culture, I would say " . $resp['special_meanings'] . ".";
            }
        }
        
        if (!empty($resp['explanation'])) {
            $cultural_belief .= " " . $resp['explanation'];
        }
        
        if (!empty($resp['stories'])) {
            $cultural_belief .= " Our community has traditional stories and sayings about dreams: " . $resp['stories'];
        }
        
        if (!empty($resp['go_to'])) {
            $cultural_belief .= " When I need dream interpretation or advice, I turn to " . $resp['go_to'] . ".";
        }
        
        if (!empty($cultural_belief)) {
            $narrative[] = $cultural_belief;
        }
    }
    
    // Personal dream experience narrative
    if (!empty($resp['dream_description']) || !empty($resp['dream_feeling'])) {
        $dream_experience = "";
        if (!empty($resp['dream_description'])) {
            $dream_experience = "One dream that stands out clearly in my memory is this: " . $resp['dream_description'];
        }
        
        if (!empty($resp['dream_feeling'])) {
            $dream_experience .= " During and after this dream, I felt " . $resp['dream_feeling'] . ".";
        }
        
        if (!empty($resp['attach_meaning'])) {
            if (strtolower($resp['attach_meaning']) == 'yes') {
                $dream_experience .= " Both I and others attached meaning to this dream.";
                if (!empty($resp['meaning_description'])) {
                    $dream_experience .= " The meaning we found was: " . $resp['meaning_description'];
                }
            } else {
                $dream_experience .= " Neither I nor others attached any particular meaning to this dream.";
            }
        }
        
        if (!empty($dream_experience)) {
            $narrative[] = $dream_experience;
        }
    }
    
    // Symbols and meanings narrative
    if (!empty($resp['symbols']) || !empty($resp['symbol_meanings'])) {
        $symbols_narrative = "";
        if (!empty($resp['symbols'])) {
            $symbols_narrative = "In my dreams, certain symbols, animals, or people appear regularly: " . $resp['symbols'] . ".";
        }
        
        if (!empty($resp['symbol_meanings'])) {
            $symbols_narrative .= " To me and my culture, these symbols mean: " . $resp['symbol_meanings'] . ".";
        }
        
        if (!empty($resp['omens_examples'])) {
            $symbols_narrative .= " In our community, certain dreams are considered omens: " . $resp['omens_examples'];
        }
        
        if (!empty($symbols_narrative)) {
            $narrative[] = $symbols_narrative;
        }
    }
    
    // Impact and rituals narrative
    if (!empty($resp['influenced']) || !empty($resp['rituals'])) {
        $impact_narrative = "";
        if (!empty($resp['influenced'])) {
            if (strtolower($resp['influenced']) == 'yes') {
                $impact_narrative = "Dreams have indeed influenced my real-life decisions and actions.";
                if (!empty($resp['influence_description'])) {
                    $impact_narrative .= " Specifically: " . $resp['influence_description'];
                }
            } else {
                $impact_narrative = "Dreams have not significantly influenced my real-life decisions or actions.";
            }
        }
        
        if (!empty($resp['rituals'])) {
            if (strtolower($resp['rituals']) == 'yes') {
                $impact_narrative .= " After certain dreams, I do perform rituals, pray, or seek help.";
                if (!empty($resp['ritual_actions'])) {
                    $impact_narrative .= " The actions I take include: " . $resp['ritual_actions'];
                }
            } else {
                $impact_narrative .= " I don't typically perform rituals or seek help after dreams.";
            }
        }
        
        if (!empty($impact_narrative)) {
            $narrative[] = $impact_narrative;
        }
    }
    
    // Sharing and consent narrative
    if (!empty($resp['share_with_ai']) || !empty($resp['consent'])) {
        $sharing_narrative = "";
        if (!empty($resp['share_with_ai'])) {
            $ai_comfort = strtolower($resp['share_with_ai']);
            if ($ai_comfort == 'yes') {
                $sharing_narrative = "I am comfortable sharing my dreams with AI for research and cultural understanding.";
            } elseif ($ai_comfort == 'no') {
                $sharing_narrative = "I am not comfortable sharing my dreams with AI for research purposes.";
            } else {
                $sharing_narrative = "Regarding sharing dreams with AI, I feel " . $resp['share_with_ai'] . ".";
            }
        }
        
        if (!empty($resp['consent'])) {
            if (strtolower($resp['consent']) == 'yes') {
                $sharing_narrative .= " I give my permission for my anonymous responses to be used to improve cultural knowledge systems.";
            } else {
                $sharing_narrative .= " I do not give permission for my responses to be used for research purposes.";
            }
        }
        
        if (!empty($resp['additional_comments'])) {
            $sharing_narrative .= " Additionally, I would like to share: " . $resp['additional_comments'];
        }
        
        if (!empty($sharing_narrative)) {
            $narrative[] = $sharing_narrative;
        }
    }
    
    // Combine all narrative parts into flowing paragraphs
    $full_narrative = implode(" ", $narrative);
    
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 8, $full_narrative, 0, 'J');
    
    // Output PDF
    $filename = 'Narrative_' . $narrativeId . '_Cultural_Dream_Story.pdf';
    $pdf->Output($filename, 'D');
}
?>

