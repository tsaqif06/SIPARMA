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
    'g4/jYU0oyuXv3skG/T170w==')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Sign In'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/svg-min-af0-88f'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/span_Wisata Anda'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/div_Galeri_row'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Edit'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input__name'), 'Kolam Ikan')

WebUI.selectOptionByValue(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/select_Pilih Tipe                          _4ef589'), 
    'wahana', true)

WebUI.selectOptionByValue(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/select_Pilih Tipe                          _4ef589'), 
    'alam', true)

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input__address'), 'Gadang')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Rp_price'), '-0.01')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Rp_children_price'), '-0.01')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Nama                                 _cd56ae'), 
    'BCA')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Nomor                                _80a64b'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/textarea_Minima ratione aut ipsam quos. Opt_d1e4c2'), 
    'lorem ipsum dolores')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Simpan'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Rp_price'), '100.000')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Rp_children_price'), '100.000')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Simpan'))

