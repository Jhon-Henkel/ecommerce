FROM node:20.19-alpine

WORKDIR /app/frontend

COPY ../../.. .

COPY ./config/develop/docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]

CMD ["npm", "run", "dev"]
