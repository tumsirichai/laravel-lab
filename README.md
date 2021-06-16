# lab-cicd-laravel

CI/CD with Laravel

## docker-compose.yml
- docker-compose.yml สร้าง build container port 3000
- docker-compose.development.yml สร้าง container ชื่อ laravel-docker-development
- docker-compose.production.yml คือเราจะสั่งให้สร้าง container ชื่อ laravel-docker-production

## สั่งรัน development
```
docker-compose -f docker-compose.yml -f docker-compose.development.yml up
```
## สั่งรัน production
```
docker-compose -f docker-compose.yml -f docker-compose.production.yml up
```

## หรือสร้าง shell script เพื่อ run ได้ง่ายขึ้น เช่น development.sh

```
docker-compose -f docker-compose.yml -f docker-compose.development.yml up
```

---

## Resources

- 
