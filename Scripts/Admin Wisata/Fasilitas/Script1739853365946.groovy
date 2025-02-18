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

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Fasilitas'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Wifi_w-32-px h-32-px bg-danger-focus_0cd0c9'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Hapus'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/a_Tempat Beribadah Untuk Muslim_w-32-px h-3_de3ece'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input__name'), 'Gereja')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/textarea_Tempat Beribadah Untuk Muslim'), 
    'Tempat Beribadah Untuk Kristen dan Katolik')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Simpan'))

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Tambah Data'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input__name'), 'Penitipan barang')

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/textarea_Deskripsi_description'), 
    'Max 20 KG')

WebUI.click(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/button_Simpan'))

WebUI.setText(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/input_Search_dt-search-0'), '')

WebUI.selectOptionByValue(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/select_102550100'), '50', 
    true)

WebUI.selectOptionByValue(findTestObject('Object Repository/Edit Wisata/Page_SIPARMA - Panel Admin/select_102550100'), '10', 
    true)

