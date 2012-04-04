var imageURL = new Array();

imageURL[0] = "img/home/portuguesOverBtn.png";
imageURL[1] = "img/home/espanhoOverBtn.png";

imageURL[2] = "pt/img/regulamento/prosseguirOverBtn.png";
imageURL[3] = "es/img/regulamento/prosseguirOverBtn.png";

imageURL[4] = "pt/img/cadastro/cadastrarOverBtn.png";
imageURL[5] = "es/img/cadastro/cadastrarOverBtn.png";
imageURL[6] = "pt/img/cadastro/procurarOverBtn.png";
imageURL[7] = "es/img/cadastro/procurarOverBtn.png";




for(var i=0; i <= imageURL.length; i++) 
{
	var image = new Image();
	image.src = imageURL[i];
}