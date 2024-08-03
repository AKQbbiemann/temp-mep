# mep

Für Infos, wie entwickelt wird ist im Wiki beschrieben: https://wiki.akquinet.de/x/yOa9Dg


Das Projekt kann sich dann lokal gecloned werden. Ins Verzeichnis vom Projekt wechseln. Wenn noch kein kind-Cluster erstellt wurde, folgendermaßen erstellen und Namespace anlegen:
```sh
kind create cluster
kubectl create namespace mep
```

Zudem muss sich noch der Postgres-Operator geklont und installiert werden. Alle nötigen Schritte dazu sind hier zu finden: https://wiki.akquinet.de/pages/viewpage.action?spaceKey=AUTO&title=Zalando+Postgres-Operator


```sh
# Chart Repo hinzufügen falls nicht vorhanden
helm repo add zalando https://opensource.zalando.com/postgres-operator/charts/postgres-operator/
helm repo update

helm install postgres-operator zalando/postgres-operator -n operators --create-namespace
```

So wird das lokale Deployment angestartet. Vorraussetzung ist, dass der Namespace 'monitoring-dashboards' existiert.
Passwörter werden über Umgebungsvariablen in das Deployment geladen. Dazu einmal am Vault anmelden und die Passwörter in die Variablen packen:

```sh
vault login -address=https://vault.team41.oss.msvc.akqui.net -method=oidc

export VAULT_ADDR=https://vault.team41.oss.msvc.akqui.net
export KEYCLOAK_CLIENT_SECRET=$(vault kv get -field=secret internal-services/datamanagement/services/webportale/keycloak)

helm uninstall mep -n mep | true && kubectl delete pvc pgdata-ai-mep-0 -n mep | true && kubectl delete pvc pgdata-ai-mep-1 -n mep | true && skaffold dev  --trigger='manual' -n mep --port-forward=true
```

Im neuen Fenster können folgendermaßen Artisan-Befehle ausgeführt werden. Der folgende bereitet die Datenbank vor.
```sh
kubectl exec -n mep $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name') -- php artisan migrate --force
kubectl exec -n mep $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name') -- php artisan schedule:run
kubectl exec -n mep $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name') -- php artisan db:seed --force
```

Initial müssen nach der ersten Installation die ServiceProvider gepublished werden
```sh
kubectl exec -n mep $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name') -- php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

Generierung der Swagger files
```sh
kubectl exec -n mep $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name') -- php artisan l5-swagger:generate
```

Bei Änderung in der Config oder bei den Routen
```sh
kubectl exec -n mep $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name') -- php artisan optimize
```


So lässt sich das composer-lock-File aus dem Container auf die lokale Umgebung ziehen.
Wird nur benötigt, wenn ihr über die package.json neue Pakete installiert habt.
Achtet darauf, das der Conatinername über kubectl get pods geholt werden muss, dieser ist hier beispielhaft drin.
```sh
    export FILENAME=app/Http/Controllers/ExampleController.php && kubectl cp $FILENAME $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name'):/var/www/html/$FILENAME -n mep

    kubectl cp $(kubectl get pods -n mep -oname -l app.kubernetes.io/name=laravel-application -ojson | jq -r '.items[].metadata.name'):/var/www/html/composer.lock ./composer.lock -n mep
```
