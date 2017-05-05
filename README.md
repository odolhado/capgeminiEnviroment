
Housible environment
========================

    Developer environment based on components:

    - PHP7
    - Nginx
    - Postgres


### Requirements::

    - docker

### Startup::

    ``` php robo ```
    ``` ./robo serup:all ```


#### Helpful commands:

 Start docker:

    ```  docker-compose up -d  ```

 Start all containers

    ```  docker start $(docker ps -q)  ```
    ```  docker restart $(docker ps -q)  ```

 Stop all containers

    ```  docker stop $(docker ps -a -q) ```

 Delete all containers

    ```  docker rm $(docker ps -aq) ```


