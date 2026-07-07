# SecureBank Lab 🏦 - Vulnerable Banking Web App

A deliberately vulnerable banking web application for **authorized** penetration testing practice. Runs in Docker — one command to start.

## 🚨 Legal Notice

**Only use on systems you own or have explicit written permission to test.**

## 🚀 Quick Start

```bash
# Prerequisites: Docker installed
git clone https://github.com/YOUR_USERNAME/securebank.git
cd securebank
docker build -t securebank .
docker run -d -p 8080:80 --name bankapp securebank
then go to web and access http://localhost:8080/bankapp
