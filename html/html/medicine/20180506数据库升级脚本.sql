/*
解决excel导出和导入的顺序一致性问题
*********************************************************************
*/
use db_medicine;
ALTER TABLE md_patient_medicine ADD COLUMN sheetnum INT DEFAULT 0;
