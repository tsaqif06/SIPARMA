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

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/input_Sign In to your Account_email'), 
    'palmer123@gmail.com')

WebUI.setEncryptedText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/input_Sign In to your Account_password'), 
    'g4/jYU0oyuXv3skG/T170w==')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/input_Sign In to your Account_email'), 
    'palmer@gmail.com')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Sign In'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/svg-min-af0-88f'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/a_Galeri'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Hapus'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Tambah Data'))

WebUI.selectOptionByValue(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/select_Pilih Tipe Gambar                   _7bc9f4'), 
    'place', true)

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Simpan'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Tempat'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Semua'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Promo'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/button_Semua'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/div_Hapus'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin (1)/div_Hapus'))

