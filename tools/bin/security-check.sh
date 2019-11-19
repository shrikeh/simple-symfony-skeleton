#!/usr/bin/env bash

function security_check()
{
    local BIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
    local FILENAME="$( basename ${BASH_SOURCE[0]} )";

    cd "${BIN_DIR}/../../";

    command -v docker-compose >/dev/null 2>&1 || {
        echo "Docker compose not found, running security check in vagrant box";
        vagrant up;
        vagrant ssh -c "bash ./tools/bin/${FILENAME}";
        return 0;
    }

    docker-compose run security-check;
}

security_check;