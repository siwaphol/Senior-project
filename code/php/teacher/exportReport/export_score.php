<?php
require_once("../../connect.php");
require_once "../../write-excel/class.writeexcel_workbook.inc.php";
require_once "../../write-excel/class.writeexcel_worksheet.inc.php";

mysql_db_query("u500510876","SET CHARACTER SET tis620");
$course = $_GET['course'];
$section = $_GET['sec'];
$year = $_GET['year'];
$semester = $_GET['sem'];


$head = iconv('UTF-8', 'TIS-620', "รายงานผลคะแนนของนักศึกษา ภาคเรียนที่ $semester ปีการศึกษา $year"); 
$id = iconv('UTF-8', 'TIS-620', "ที่");
$code = iconv('UTF-8', 'TIS-620', "รหัสนักศึกษา");
$fullname = iconv('UTF-8', 'TIS-620', "ชื่อ - นามสกุล");


	$sql1 = "SELECT s.studentId As studentId,studentName FROM student As s,register As r WHERE s.studentId=r.studentId AND r.courseId='$course' AND r.sectionId='$section' ORDER BY s.studentId ASC";
	$sql2 = "SELECT courseName FROM course WHERE courseId='$course'";
	$sql3 = "SELECT teacherName FROM teacher As t,course_section As c WHERE t.teacherId=c.teacherId AND c.courseId='$course' AND c.sectionId='$section'";
	$sql4 = "SELECT homeworkId FROM homework_assignment WHERE courseId='$course' ";	
	
	$result1 = mysql_query($sql1) or die ("sql1 : ".mysql_error());
	$result2 = mysql_query($sql2) or die ("sql2 : ".mysql_error());
	$result3 = mysql_query($sql3) or die ("sql3 : ".mysql_error());
	$result4 = mysql_query($sql4) or die ("sql4 : ".mysql_error());
	
	$row2 = mysql_fetch_array($result2);
	$row3 = mysql_fetch_array($result3);
           
			$fname = tempnam("/tmp", "test.xls");
            $workbook =& new writeexcel_workbook($fname);

            $worksheet =& $workbook->addworksheet();
            //$worksheet->set_margin_right(0.50);
            //$worksheet->set_margin_bottom(1.10);

            ## Set Format  ##
			// ส่วน header หัวข้อบนสุด
            $xlscelldesc_header =& $workbook->addformat();
            $xlscelldesc_header->set_font('Angsana New');
            $xlscelldesc_header->set_size(18);
            $xlscelldesc_header->set_color('black');
            $xlscelldesc_header->set_bold(1);
            $xlscelldesc_header->set_text_v_align(1);
            $xlscelldesc_header->set_merge(1);
			// ส่วน header ย่อย 
			$xlscelldesc_sub_header =& $workbook->addformat();
            $xlscelldesc_sub_header->set_font('Angsana New');
            $xlscelldesc_sub_header->set_size(14);
            $xlscelldesc_sub_header->set_color('black');
            $xlscelldesc_sub_header->set_bold(1);
			$xlscelldesc_sub_header->set_align('left');
            $xlscelldesc_sub_header->set_text_v_align(1);
			// ส่วนแถบหัวตาราง
			$table_header =& $workbook->addformat();
            $table_header->set_font('Angsana New');
            $table_header->set_size(14);
            $table_header->set_color('black');
            $table_header->set_bold(1);
			$table_header->set_border(1);
			$table_header->set_align('center');
            $table_header->set_text_v_align(1);
			$table_header->set_fg_color('silver');
			// ส่วนข้อมูล ชิดซ้าย
            $xlsCellDesc_left =& $workbook->addformat();
            $xlsCellDesc_left->set_font('Angsana New');
            $xlsCellDesc_left->set_size(14);
            $xlsCellDesc_left->set_color('black');
			$xlsCellDesc_left->set_border(1);
            $xlsCellDesc_left->set_align('left');
            $xlsCellDesc_left->set_text_v_align(1);
			// ส่วนข้อมูล จัดกลาง
			$xlsCellDesc_center =& $workbook->addformat();
            $xlsCellDesc_center->set_font('Angsana New');
            $xlsCellDesc_center->set_size(14);
            $xlsCellDesc_center->set_color('black');
			$xlsCellDesc_center->set_border(1);
            $xlsCellDesc_center->set_align('center');
            $xlsCellDesc_center->set_text_v_align(1);
			// section
			$sec =& $workbook->addformat();
            $sec->set_font('Angsana New');
            $sec->set_size(14);
            $sec->set_color('black');
			$sec->set_bold(1);
            $sec->set_align('left');
            $sec->set_text_v_align(1);
			$sec->set_num_format('000');
			// date
			$date =& $workbook->addformat();
            $date->set_font('Angsana New');
            $date->set_size(14);
            $date->set_color('black');
            $date->set_bold(1);
			$date->set_align('left');
            $date->set_text_v_align(1);
			$date->set_num_format('dd/mm/yyyy');
            ## End of Set Format ##

            ## Set Column Width & Height  กำหนดความกว้างของ Cell
            $worksheet->set_column('A:A', 2.00);
            $worksheet->set_column('B:B', 4.00);
            $worksheet->set_column('C:C', 11.29);
            $worksheet->set_column('D:D', 40.00);
			$worksheet->set_column('E:Z', 5.00);

            ## Writing Data  เพิ่มข้อมูลลงใน Cell
            $worksheet->write_blank(A1,$xlscelldesc_header);
            $worksheet->write(B1,"$head", $xlscelldesc_header);
            $worksheet->write_blank(C1,$xlscelldesc_header);
            $worksheet->write_blank(D1,$xlscelldesc_header);
            $worksheet->write_blank(E1,$xlscelldesc_header);
            $worksheet->write_blank(F1,$xlscelldesc_header);
			$worksheet->write_blank(G1,$xlscelldesc_header);
            
            $worksheet->write(B2, "COURSE : ", $xlscelldesc_sub_header);
            $worksheet->write(D2, "$course", $xlscelldesc_sub_header);
			
			$worksheet->write(B3, "TITLE : ", $xlscelldesc_sub_header);
            $worksheet->write(D3, "$row2[courseName]", $xlscelldesc_sub_header);

			$worksheet->write(B4, "SECTION : ", $xlscelldesc_sub_header);
            $worksheet->write(D4, "$section", $sec);
			
			$worksheet->write(B5, "LECTURER : ", $xlscelldesc_sub_header);
            $worksheet->write(D5, "$row3[teacherName]", $xlscelldesc_sub_header);
			
			$worksheet->write(B6, "DATE : ", $xlscelldesc_sub_header);
            $worksheet->write(D6, date("d/m/Y"), $date);
			
            $worksheet->write(B7,"$id", $table_header); 
            $worksheet->write(C7,"$code", $table_header);
            $worksheet->write(D7,"$fullname", $table_header);
            
			$col = 4 ;
			for($k=0;$k<mysql_num_rows($result4);$k++){
				$row4 = mysql_fetch_array($result4);
				$worksheet->write(6, $col, "$row4[homeworkId]", $table_header);
				$col++;
			}
	
			$xlsRow = 8;
            # ตรงนี้คือดึงข้อมูลจาก mysql มาใส่ใน Cell
                while($row1 = mysql_fetch_array($result1)) {
					$student = $row1['studentId'] ;
                    ++$i;
                        $worksheet->write("B$xlsRow", "$i", $xlsCellDesc_center);
                        $worksheet->write("C$xlsRow", "$row1[studentId]", $xlsCellDesc_center); 
                        $worksheet->write("D$xlsRow", "$row1[studentName]", $xlsCellDesc_left);
                
					$j = 4;
					$result4 = mysql_query($sql4);
					while($hwid = mysql_fetch_array($result4)){
					
						$sql5 = "SELECT checkScore FROM homework_sending 
							WHERE studentId ='$student' AND courseId='$course' AND homeworkId='$hwid[homeworkId]' ";
						$result5 = mysql_query($sql5) or die ("sql5 : ".mysql_error());	
						$row5 = mysql_fetch_array($result5);
							if(mysql_num_rows($result5) == 0 || $row5['checkScore'] == 0)
								$score = 0;
							else
								$score = $row5['checkScore'];
								
						$index = $xlsRow ;		
						$worksheet->write($index-1, $j, "$score", $xlsCellDesc_center);
					$j++;
					}
				
				$xlsRow++;
                }

            # เสร็จแล้วก็ส่งไฟล์ไปยัง Browser ครับแค่นี้ก็เสร็จแล้ว
            $workbook->close();

header("Content-Type: application/x-msexcel; name=\"score_$course-$section.xls\"");
header("Content-Disposition: attachment; filename=\"score_$course-$section.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);

?>