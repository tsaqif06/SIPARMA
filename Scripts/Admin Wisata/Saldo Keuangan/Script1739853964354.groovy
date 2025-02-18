import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testng.keyword.TestNGBuiltinKeywords as TestNGKW
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.Keys as Keys

WebUI.openBrowser('')

WebUI.navigateToUrl('http://127.0.0.1:8000/admin/login')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Sign In to your Account_email'), 
    'palmer@gmail.com')

WebUI.setEncryptedText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Sign In to your Account_password'), 
    '//bFq0pcdIHMYj+PULg1tQ==')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Sign In'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Sign In to your Account_email'), 
    'palmer@gmail.com')

WebUI.setEncryptedText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Sign In to your Account_password'), 
    'g4/jYU0oyuXv3skG/T170w==')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/span_Sign In to your Account_toggle-passwor_be3dde'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Sign In'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/svg-min-af0-88f'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/span_Saldo Keuangan'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Pencairan Saldo'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/div_light                                  _e67bd6'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/svg-min-af0-88f'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Saldo Keuangan'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Rekap Saldo'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/td_February'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/path-min-af0-a11'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Saldo'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Rp 23.000_w-32-px h-32-px bg-primary-ligh_6a70a6'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/svg-min-af0-88f'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/span_Pencairan Saldo'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/span_Pending'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/svg-min-6f5-206'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Jumlah Uang                          _eec1a5'), 
    '5000.00')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Setujui'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/div_Data Permintaan Pencairan Saldo        _a6471b'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Tambah Permimtaan'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Jumlah Uang                          _eec1a5'), 
    '5000.00')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Setujui'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Search_dt-search-0'), '')

