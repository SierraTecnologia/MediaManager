# Infra Estrutura

---

- [Docker Compose](#infra-dockercompose)
- [Terraform](#infra-terraform)

<a name="infra-dockercompose"></a>

## Configuração do DockerComposer

Arquivo DOckerCOmpose para producao segue na pasta raiz do projeto, assim como o arquivo de Configuração do nginx e a receita do container de php do docker 


```
    image: sierratecnologia/nginx
    image: sierratecnologia/php:7.3
    image: redis:5.0
```


<a name="infra-terraform"></a>
## Configuração do Terraform

```
#########################
# AWS CONFIG
#########################
provider "aws" {
  access_key = "{AWS_ACCESS_KEY}"
  secret_key = "{AWS_SECRET_KEY}"
  region = "sa-east-1"
}

#########################
# INSTANCIAS
#########################
resource "aws_instance" "rica" {
  ami = "ami-03c6239555bb12112"
  instance_type = "t2.micro"
  key_name = "RiCa-amazon"

  tags {
    Name = "rica"
  }

  vpc_security_group_ids = [
    "${aws_security_group.http-group.id}",
    "${aws_security_group.https-group.id}",
    "${aws_security_group.ssh-group.id}",
    "${aws_security_group.all-outbound-traffic.id}",
  ]

  user_data = "${file("execute_in_server/docker_install.sh")}"

  # provisioner "file" {
  #   source      = "execute_in_server/docker_install.sh"
  #   destination = "/tmp/docker_install.sh"
  # }

  # provisioner "remote-exec" {
  #   inline = [
  #     "chmod +x /tmp/docker_install.sh",
  #     "/tmp/docker_install.sh",
  #   ]
  # }
}

#########################
## EXECUTE ANSIBLE
#########################
resource null_resource "ansible_web" {
  depends_on = [
    "aws_instance.rica"
  ]

  provisioner "local-exec" {
    command = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -u ec2-user --private-key '/sierra/RiCa/Infra/keys/ricaserverkey-amazon.pem' -i '${aws_instance.rica.public_ip}' ansible/main.yml"
  }
}


#########################
## IPS PUBLICOS
#########################
resource "aws_eip" "rica-ip" {
  instance = "${aws_instance.rica.id}"
}

#########################
## IPS SECURITYS GROUPS
#########################
resource "aws_security_group" "https-group" {
  name = "https-access-group"
  description = "Allow traffic on port 443 (HTTPS)"

  tags = {
    Name = "HTTPS Inbound Traffic Security Group"
  }

  ingress {
    from_port = 443
    to_port = 443
    protocol = "tcp"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
  }
}

resource "aws_security_group" "http-group" {
  name = "http-access-group"
  description = "Allow traffic on port 80 (HTTP)"

  tags = {
    Name = "HTTP Inbound Traffic Security Group"
  }

  ingress {
    from_port = 80
    to_port = 80
    protocol = "tcp"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
  }
}

resource "aws_security_group" "all-outbound-traffic" {
  name = "all-outbound-traffic-group"
  description = "Allow traffic to leave the AWS instance"

  tags = {
    Name = "Outbound Traffic Security Group"
  }

  egress {
    from_port = 0
    to_port = 0
    protocol = "-1"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
  }
}

resource "aws_security_group" "ssh-group" {
  name = "ssh-access-group"
  description = "Allow traffic to port 22 (SSH)"

  tags = {
    Name = "SSH Access Security Group"
  }

  ingress {
    from_port = 22
    to_port = 22
    protocol = "tcp"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
  }
}
```